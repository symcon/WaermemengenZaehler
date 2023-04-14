<?php

declare(strict_types=1);
    class WaermemengenZaehler extends IPSModule
    {
        public function Create()
        {
            //Never delete this line!
            parent::Create();

            $this->RegisterPropertyInteger('TemperatureVariableInputID', 1);
            $this->RegisterPropertyInteger('TemperatureVariableOutputID', 1);
            $this->RegisterPropertyInteger('WaterMeterID', 1);

            $this->RegisterTimer('UpdateWaterMeter', 15 * 60 * 1000, 'WMZ_UpdatePower($_IPS[\'TARGET\']); WMZ_UpdateEnergy($_IPS[\'TARGET\']);');

            $this->RegisterVariableFloat('Power', $this->Translate('Power'), '~Watt.3680', 0);
            $this->RegisterVariableFloat('Energy', $this->Translate('Energy'), '~Electricity', 1);
        }

        public function Destroy()
        {
            //Never delete this line!
            parent::Destroy();
        }

        public function ApplyChanges()
        {
            //Never delete this line!
            parent::ApplyChanges();

            //Validate the Variables
            $temperatureIn = $this->ReadPropertyInteger('TemperatureVariableInputID');
            $temperatureOut = $this->ReadPropertyInteger('TemperatureVariableOutputID');
            $waterMeter = $this->ReadPropertyInteger('WaterMeterID');

            if (!@IPS_VariableExists($temperatureIn)
                || !@IPS_VariableExists($temperatureOut)
                || !@IPS_VariableExists($waterMeter)
            ) {
                $this->SetStatus(201); //Some Variables are not exist
                return;
            }

            $temperatureInProfile = $this->GetProfile($temperatureIn);
            $temperatureOutProfile = $this->GetProfile($temperatureOut);
            $waterMeterProfile = $this->GetProfile($waterMeter);

            if (IPS_GetVariableProfile($temperatureInProfile)['Prefix'] == '°C' || IPS_GetVariableProfile($temperatureOutProfile)['Prefix'] == '°C') {
                $this->SetStatus(202); //False profile on one of the temperature variables
                return;
            }
            if (IPS_GetVariableProfile($waterMeterProfile)['Prefix'] == 'm³' || IPS_GetVariableProfile($waterMeterProfile)['Prefix'] == 'Liter') {
                $this->SetStatus(203); //False profile on the water meter variable
                return;
            }
            $this->SetStatus(102);

            //First calculation of the Power and the Energy
            $this->UpdatePower();
            $this->UpdateEnergy();
            $this->SetTimerInterval('UpdateWaterMeter', 15 * 60 * 1000);
        }

        public function UpdatePower()
        {
            $temperatureIn = GetValue($this->ReadPropertyInteger('TemperatureVariableInputID'));
            $temperatureOut = GetValue($this->ReadPropertyInteger('TemperatureVariableOutputID'));
            $waterMeter = GetValue($this->ReadPropertyInteger('WaterMeterID'));

            //Delta T = temperatureOut - temperatureIn
            //P = (m * c * Delta T) / t
            //c = 4181 Joule/(Kg*K) <- Specific heat capacity of water

            $power = ($waterMeter * 4181 * ($temperatureOut - $temperatureIn)) / 15 * 60;

            $this->SetValue('Power', $power);
        }

        public function UpdateEnergy()
        {
            $power = $this->GetValue('Power');
            $temperature = GetValue($this->ReadPropertyInteger('TemperatureVariableOutputID')) - GetValue($this->ReadPropertyInteger('TemperatureVariableInputID'));

            //E = (P * Delta T ) / 3600 * 1000
            $energy = ($power * $temperature) / 3600 * 1000;

            $this->SetValue('Energy', $energy);
        }

        private function GetProfile(int $variableID)
        {
            $variable = IPS_GetVariable($variableID);
            if ($variable['VariableCustomProfile'] != '') {
                return $variable['VariableCustomProfile'];
            } else {
                return $variable['VariableProfile'];
            }
        }
    }
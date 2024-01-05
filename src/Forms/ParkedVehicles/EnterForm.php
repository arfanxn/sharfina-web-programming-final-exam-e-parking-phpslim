<?php

namespace App\Forms\ParkedVehicles;

class EnterForm extends ParkedVehicleForm
{
    use \App\Traits\FormTrait;

    private string $vehicleColorId;
    private string $vehicleTypeId;

    public function getRules(): array
    {
        return [
            'vehicle_color_id' => 'required',
            'vehicle_type_id' => 'required',
            'plate_number' => 'required|regex:/^[A-Z]{1,2}\s\d{1,4}\s[A-Z]{1,3}$/',
        ];
    }

    /**
     * @return string
     */
    public function getVehicleColorId(): string
    {
        return $this->vehicleColorId;
    }
    /**
     * @param string $vehicleColorId
     * @return void
     */
    public function setVehicleColorId(string $vehicleColorId): void
    {
        $this->vehicleColorId = $vehicleColorId;
    }

    /**
     * @return string
     */
    public function getVehicleTypeId(): string
    {
        return $this->vehicleTypeId;
    }
    /**
     * @param string $vehicleTypeId
     * @return void
     */
    public function setVehicleTypeId(string $vehicleTypeId): void
    {
        $this->vehicleTypeId = $vehicleTypeId;
    }
}

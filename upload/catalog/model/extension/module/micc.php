<?php

class ModelExtensionModuleMicc extends Model {
    public function getById($manufacturer_id){
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "manufacturer_country WHERE manufacturer_id = '$manufacturer_id'");
        return $query->row;
    }
}
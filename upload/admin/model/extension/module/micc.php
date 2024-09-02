<?php
class ModelExtensionModuleMicc extends Model {
    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS`" . DB_PREFIX . "manufacturer_country` (
				`manufacturer_country_id` INT(11) NOT NULL AUTO_INCREMENT,
				`manufacturer_id` INT(11),
                `manufacturer_name` VARCHAR(100),
				`img` VARCHAR(100),
				`description` TEXT,
				PRIMARY KEY (`manufacturer_country_id`,`manufacturer_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

        $query = $this->db->query("SELECT DISTINCT manufacturer_id, name, image FROM " . DB_PREFIX . "manufacturer");
        $manufacturers= $query->rows;
        foreach ($manufacturers as $manufacturer) {
            $mId = $manufacturer['manufacturer_id'];
            $mName = $manufacturer['name'];
            $mImage = $manufacturer['image'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_country SET manufacturer_id = '$mId', manufacturer_name='$mName', img = '$mImage'");
        }
    }

    public function update($array = []){
        if(!empty( $array )){
            $b = 0;
            for ($i = 1; $i < count($array); $i++) {
                $desc =  $array[$b];
                $this->db->query("UPDATE " . DB_PREFIX . "manufacturer_country SET description='$desc' WHERE manufacturer_country_id = '$i'");
                $b++;
            }
        }
    }
    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "manufacturer_country`");
    }

    public function getAll(){
        $query = $this->db->query("SELECT DISTINCT manufacturer_country_id, manufacturer_id, manufacturer_name, img, description FROM ". DB_PREFIX . "manufacturer_country");
        $manufacturers= $query->rows;
        return $manufacturers;
    }

    public function test(){
        $query = $this->db->query("SELECT DISTINCT manufacturer_id,image FROM " . DB_PREFIX . "manufacturer");
        $manufacturers= $query->rows;
        return $manufacturers;
    }
}
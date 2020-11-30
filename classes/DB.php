<?php

class DB {
	
	public $db;
    
    public function __construct($settings) {
        try {
            $this->db = new PDO('mysql:dbname='.$settings['dbname'].';host='.$settings['host'], $settings['username'], $settings['password']);
        } catch (PDOException $e) {
            throw new Exception('Подключение к БД не удалось: ' . $e->getMessage());
        }
    }
	
	public function get_sizes($with_keys = false) {
	    $result = [];
        $query = $this->db->prepare('SELECT * FROM sizes ORDER BY id DESC');
        if ( !$query->execute() ) {
            throw new Exception('Запрос к БД не удался');    
        }
        else {
            $result = $query->fetchAll();
            if ( $with_keys ) {
                $keys = array_column($result, 'name');
                $result = array_combine($keys, $result);
            }
        }
        return $result;
    }
    
}
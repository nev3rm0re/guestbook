<?php
    class GuestbookPdo
    {
        private $pdo = null;
        public function __construct($options) {
            try {
                $this->pdo = new PDO(
                    $options['dsn'],
                    $options['username'],
                    $options['password'],
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"',
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) {
                throw new StorageException($e->getMessage());
            }

        }

        public function findAll($entity) {
            $table_name = $this->map($entity);
            $stmt = $this->pdo->prepare("SELECT * FROM $table_name");
            $stmt->execute();

            $result = array();

            $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($all as $row) {
                $object = new $entity();
                $object->hydrate($row);
                $object->setStorage($this);
                $result[] = $object;
            }

            return $result;
        }

        public function findBy($object, $field, $value)
        {
            $table_name = $this->map($object);

            $sql = "SELECT * FROM $table_name WHERE $field = ?";
            $stmt = $this->pdo->prepare($sql);
            try {
                $stmt->execute(array($value));
            } catch (PDOException $e) {
                throw new StorageException($e);
            }

            $object = new $object();
            $object->setStorage($this);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data !== false) {
                $object->hydrate($data);
                return $object;
            } else {
                return false;
            }
        }

        public function findAllBy($object_name, $field, $value)
        {
            $table_name = $this->map($object_name);
            $sql = "SELECT * FROM $table_name WHERE $field = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array($value));

            $objects = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $object = new $object_name;
                $object->setStorage($this);
                $object->hydrate($row);
                $objects[] = $object;
            }

            return $objects;
        }

        public function map($entity) {
            if (is_object($entity)) {
                $entity = get_class($entity);
            }
            $map = array(
                'GuestbookPost' => 'guestbook_post',
                'GuestbookUser' => 'guestbook_user'
            );

            return $map[$entity];
        }

        public function create($class) {
            $object = new $class;
            $object->setStorage($this);

            return $object;
        }

        public function save($object) {
            $table_name = $this->map(get_class($object));

            $fields = $object->toArray();

            $set = $values = array();

            foreach ($fields as $key => $value) {
                $set[] = "`$key` = ?";
                $values[] = $value;
            }

            $sql = "INSERT INTO $table_name SET " . implode(', ', $set);
            $stmt = $this->pdo->prepare($sql);
            try {
                $stmt->execute($values);
            } catch (PDOException $e) {
                throw new StorageException($e->getMessage());
            }

            return $this->load($object, $this->pdo->lastInsertId());
        }

        public function load($object, $id) {
            return $this->findBy($object, 'id', $id, array('limit' => 1));
        }
    }

    class StorageException extends Exception {}
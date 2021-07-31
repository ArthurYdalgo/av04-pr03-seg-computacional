<?php

/**
 * PR03
 * 
 * Integrantes:
 * 
 * - Arthur de Aguiar Ydalgo Miranda Couto - 1825160
 * - João Pedro Pastório - 1912895
 * - Vinicius Pinheiro Winter - 1826506
 */

class Enygma
{
    /**
     * iv
     * 
     * A non-NULL Initialization Vector. See more at https://www.php.net/manual/en/function.openssl-encrypt
     */
    private $iv = "fiv34urv3rfvu34v";

    /**
     * Encrypted and decrypted data
     */
    private $encrypted_data, $decrypted_data;

    /**
     * AES-256 methods available
     */
    private $aes_256_methods = ['ecb' => "aes-256-ecb", 'cbc' => "aes-256-cbc"];

    /**
     * Set encrypted data
     * 
     * Sets the encrypted data
     * 
     * @param String $data
     */
    private function setEncryptedData(String $data)
    {
        $this->encrypted_data = $data;
    }

    /**
     * Get encrypted data
     * 
     * Gets the encrypted data
     * 
     * @return String encrypted data
     */
    private function getEncryptedData()
    {
        return $this->encrypted_data;
    }

    /**
     * Set decrypted data
     * 
     * Sets the decrypted data
     * 
     * @param String $data
     */
    private function setDecryptedData(String $data)
    {
        $this->decrypted_data = $data;
    }

    /**
     * Get decrypted data
     * 
     * Gets the decrypted data
     * 
     * @return String decrypted data
     */
    private function getDecryptedData()
    {
        return $this->decrypted_data;
    }

    /** 
     * Load file
     * 
     * Loads content from file
     * 
     * @param String $file_name Name of the file
     * 
     * @return String $content Content of the file
     */
    private function loadFile($file_name)
    {
        try {
            $content = file_get_contents($file_name);
            return $content;
        } catch (\Throwable $th) {
            echo "Aviso: " . $th->getMessage() . "\n";;
            return '';
        }
    }

    /**
     * Save to file
     * 
     * Saves encrypted data to file
     * 
     * @param String $file_name Name of the destination file
     * 
     * @return Integer|null Number of bytes stored, or null in case it failed
     */
    public function saveToFile($file_name)
    {
        try {
            return file_put_contents($file_name, $this->getEncryptedData());
        } catch (\Throwable $th) {
            echo "Aviso: " . $th->getMessage() . "\n";
            return null;
        }
    }

    /**
     * Encrypt
     * 
     * Encrypts a given data
     * 
     * @param String $data Data to be encrypted
     * @param String $encrypt_key Key to encrypt the data
     * @param String $method Method of encryption. Available: ecb and cbc
     * 
     * @return String $encrypted_data Encrypted data
     */
    public function encrypt(String $data, String $encrypt_key, String $method)
    {
        if (!isset($this->aes_256_methods[$method])) {
            $method = 'ecb';
        }

        $cipher = $this->aes_256_methods[$method];

        $encrypted_data = openssl_encrypt($data, $cipher, $encrypt_key, $options = 0, $method == 'cbc' ? $this->iv : '');

        $this->setEncryptedData($encrypted_data);

        return $encrypted_data;
    }

    /**
     * Encrypt
     * 
     * Encrypts a given data
     * 
     * @param String $file_name File that contains the data to be encrypted
     * @param String $encrypt_key Key to encrypt the data
     * @param String $method Method of encryption. Available: ecb and cbc
     * 
     * @return String $encrypted_data Encrypted data
     */
    public function encryptFromFile(String $file_name, String $encrypt_key, String $method)
    {
        if (!isset($this->aes_256_methods[$method])) {
            $method = 'ecb';
        }

        $data = $this->loadFile($file_name);

        $cipher = $this->aes_256_methods[$method];

        $encrypted_data = openssl_encrypt($data, $cipher, $encrypt_key, $options = 0, $method == 'cbc' ? $this->iv : '');

        $this->setEncryptedData($encrypted_data);

        return $encrypted_data;
    }

    /**
     * Decrypt
     * 
     * Decrypts a given data
     * 
     * @param String $data Data to be decrypted
     * @param String $encrypt_key Key to decrypt the data
     * @param String $method Method of encryption. Available: ecb and cbc
     * 
     * @return String $encrypted_data Decrypted data
     */
    public function decrypt(String $data, String $encrypt_key, String $method)
    {
        if (!isset($this->aes_256_methods[$method])) {
            $method = 'ecb';
        }

        $cipher = $this->aes_256_methods[$method];

        $decrypted_data = openssl_decrypt($data, $cipher, $encrypt_key , $options = 0, $method == 'cbc' ? $this->iv : '');

        $this->setDecryptedData($decrypted_data);

        return $decrypted_data;
    }

    /**
     * Decrypt
     * 
     * Decrypts a given data
     * 
     * @param String $file_name File that contains the data to be decrypted
     * @param String $encrypt_key Key to decrypt the data
     * @param String $method Method of encryption. Available: ecb and cbc
     * 
     * @return String $encrypted_data Decrypted data
     */
    public function decryptFromFile(String $file_name, String $encrypt_key, String $method)
    {
        if (!isset($this->aes_256_methods[$method])) {
            $method = 'ecb';
        }

        $data = $this->loadFile($file_name);

        $cipher = $this->aes_256_methods[$method];

        $decrypted_data = openssl_decrypt($data, $cipher, $encrypt_key , $options = 0, $method == 'cbc' ? $this->iv : '');

        $this->setDecryptedData($decrypted_data);

        return $decrypted_data;
    }

    /** 
     * Select
     * 
     * Menu function
     * 
     * @param $options Options to be selected from
     * 
     * @return mixed $selected Selected option
     */
    public function select($options)
    {
        $selected = null;

        foreach ($options as $option_key => $option_text) {
            echo "[$option_key] - $option_text\n";
        }

        do {
            $selected = readline("Selecione uma opção: ");
        } while (!isset($options[$selected]));

        return $selected;
    }


    public function output($action, $parameters)
    {

        if ($action == 'encrypt') {
            switch ($parameters['file_or_direct_input']) {
                case 0:
                    $data = $this->encrypt($parameters['data'], $parameters['key'], $parameters['method']);
                    break;

                default:
                    $data = $this->encryptFromFile($parameters['data'], $parameters['key'], $parameters['method']);
                    break;
            }
        } else {
            switch ($parameters['file_or_direct_input']) {
                case 0:
                    $data = $this->decrypt($parameters['data'], $parameters['key'], $parameters['method']);
                    break;

                default:
                    $data = $this->decryptFromFile($parameters['data'], $parameters['key'], $parameters['method']);
                    break;
            }
        }

        echo "Resultado: $data\n";
        
        if($action == 'encrypt'){
            echo "Deseja exportar para um arquivo?\n";
            $write_to_file = $this->select([1 => 'Sim', 0 => 'Não']);
    
            if ($write_to_file) {
                $output_file_name = readline("Insira o nome do arquivo de saida: ");
                $this->saveToFile($output_file_name);
            }
        }
    }

    public function decryptForm()
    {
        echo "Para decriptar alguma informação, selecione os parâmetros a seguir\n";

        // File or direct input
        $file_or_direct_input = $this->select([0 => 'Inserir no terminal', 1 => 'Arquivo (nome com extensão. Certifique-se que o mesmo se encontra no mesmo diretório de execução desse programa)']);

        // Data or file name
        $question = $file_or_direct_input ? 'Insira o nome do arquivo: ' : 'Insira o texto a ser decriptografado: ';
        $data = readline($question);

        // AES-256 method
        $method = $this->select(['ecb' => 'AES-256-ECB', 'cbc' => 'AES-256-CBC']);

        // Encryption/Decryption key
        $key = readline("Insira a chave: ");

        $this->output('decrypt', compact('file_or_direct_input', 'data', 'method', 'key'));
    }

    public function encryptForm()
    {
        echo "Para encriptar alguma informação, selecione os parâmetros a seguir\n";

        // File or direct input
        $file_or_direct_input = $this->select([0 => 'Inserir no terminal', 1 => 'Arquivo (nome com extensão. Certifique-se que o mesmo se encontra no mesmo diretório de execução desse programa)']);

        // Data or file name
        $question = $file_or_direct_input ? 'Insira o nome do arquivo: ' : 'Insira o texto a ser criptografado: ';
        $data = readline($question);

        // AES-256 method
        $method = $this->select(['ecb' => 'AES-256-ECB', 'cbc' => 'AES-256-CBC']);

        // Encryption/Decryption key
        $key = readline("Insira a chave: ");

        $this->output('encrypt', compact('file_or_direct_input', 'data', 'method', 'key'));
    }

    public function main()
    {
        $encrypt_or_decrypt = null;
        $encrypt_or_decrypt_options = [1 => 'Encriptar', 2 => 'Decriptar', 0 => 'Sair'];


        while ($encrypt_or_decrypt !== 0) {
            echo "\n\nInício - Selecione uma opção: \n";
            $encrypt_or_decrypt = $this->select($encrypt_or_decrypt_options);

            switch ($encrypt_or_decrypt) {
                case 1:
                    $this->encryptForm();
                    break;
                case 2:
                    $this->decryptForm();
                    break;
            }
        }
    }
}


$enygma = new Enygma();

$enygma->main();

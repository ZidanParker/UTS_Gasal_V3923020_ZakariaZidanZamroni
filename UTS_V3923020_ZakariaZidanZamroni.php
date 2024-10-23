<?php  
// Fungsi untuk mengenkripsi/dekripsi huruf tunggal menggunakan Caesar Cipher
function cipher($char, $key){
    if (ctype_alpha($char)) { // Jika karakter adalah huruf
        $base = ord(ctype_upper($char) ? 'A' : 'a'); // Tentukan base A/a untuk huruf besar/kecil
        $new_char = ord($char);
        $shifted = fmod($new_char + $key - $base, 26); // Shift dengan Caesar
        return chr($shifted + $base); // Kembalikan karakter setelah enkripsi/dekripsi
    } else {
        return $char; // Jika bukan huruf, kembalikan karakter asli
    }
} 

// Fungsi untuk mengenkripsi teks dengan Caesar Cipher
function enkripsi_caesar($input, $key){
    $output = "";
    foreach(str_split($input) as $char){
        $output .= cipher($char, $key); // Panggil fungsi cipher untuk setiap karakter
    }
    return $output;
}

// Fungsi untuk mendekripsi teks dengan Caesar Cipher
function dekripsi_caesar($input, $key){
    return enkripsi_caesar($input, 26 - $key); // Dekripsi adalah enkripsi dengan kunci terbalik
}

// Fungsi untuk mengenkripsi teks dengan Vigenère Cipher
function vigenere_encrypt($plaintext, $key) {
    $ciphertext = "";
    $key = strtoupper($key); // Pastikan kunci dalam huruf besar
    $key_len = strlen($key);
    $key_index = 0;

    foreach (str_split($plaintext) as $char) {
        if (ctype_alpha($char)) {
            $shift = ord($key[$key_index % $key_len]) - ord('A'); // Tentukan pergeseran dari kunci
            if (ctype_lower($char)) {
                $ciphertext .= chr(((ord($char) - ord('a') + $shift) % 26) + ord('a')); // Enkripsi huruf kecil
            } else {
                $ciphertext .= chr(((ord($char) - ord('A') + $shift) % 26) + ord('A')); // Enkripsi huruf besar
            }
            $key_index++; // Geser index kunci
        } else {
            $ciphertext .= $char; // Jika bukan huruf, tetap masukkan karakter asli
        }
    }
    return $ciphertext;
}

// Fungsi untuk mendekripsi teks dengan Vigenère Cipher
function vigenere_decrypt($ciphertext, $key) {
    $plaintext = "";
    $key = strtoupper($key);
    $key_len = strlen($key);
    $key_index = 0;

    foreach (str_split($ciphertext) as $char) {
        if (ctype_alpha($char)) {
            $shift = ord($key[$key_index % $key_len]) - ord('A');
            if (ctype_lower($char)) {
                $plaintext .= chr(((ord($char) - ord('a') - $shift + 26) % 26) + ord('a'));
            } else {
                $plaintext .= chr(((ord($char) - ord('A') - $shift + 26) % 26) + ord('A'));
            }
            $key_index++;
        } else {
            $plaintext .= $char;
        }
    }
    return $plaintext;
}

// Fungsi gabungan enkripsi Caesar dan Vigenère
function enkripsi_combination($input, $caesar_key, $vigenere_key) {
    $caesar_encrypted = enkripsi_caesar($input, $caesar_key); // Enkripsi dengan Caesar
    return vigenere_encrypt($caesar_encrypted, $vigenere_key); // Lanjut dengan Vigenère
}

// Fungsi gabungan dekripsi Vigenère dan Caesar
function dekripsi_combination($input, $caesar_key, $vigenere_key) {
    $vigenere_decrypted = vigenere_decrypt($input, $vigenere_key); // Dekripsi dengan Vigenère
    return dekripsi_caesar($vigenere_decrypted, $caesar_key); // Lanjut dengan Caesar
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTS SKD</title>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Roboto', sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #007acc;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            background-color: #f0f4f8;
            border: 1px solid #ccc;
            border-radius: 8px;
            color: #333;
            font-size: 1em;
        }
        textarea {
            height: 100px;
            resize: none;
        }
        .btn {
            background-color: #007acc;
            border: none;
            padding: 12px 18px;
            color: white;
            cursor: pointer;
            border-radius: 8px;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn:hover {
            background-color: #005fa3;
            transform: translateY(-3px);
        }
        .footer {
            margin-top: 20px;
            font-size: 0.85em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>UJIAN TENGAH SEMESTER<br>SISTEM KEAMANAN DATA</h1>
        <form method="post">
            <input type="text" name="plain" placeholder="Masukkan kalimat" required />
            <input type="number" name="caesar_key" placeholder="Masukkan kunci Caesar (0-25)" min="0" max="25" required />
            <input type="text" name="vigenere_key" placeholder="Masukkan kunci Vigenère" required />
            <button type="submit" name="enkripsi" class="btn">Enkripsi</button>
            <button type="submit" name="dekripsi" class="btn">Dekripsi</button>
            <br>
            <br>
            <textarea readonly placeholder="Hasil">
                <?php  
                    if (isset($_POST["enkripsi"])) { 
                        echo enkripsi_combination($_POST["plain"], $_POST["caesar_key"], $_POST["vigenere_key"]);
                    } else if (isset($_POST["dekripsi"])) {
                        echo dekripsi_combination($_POST["plain"], $_POST["caesar_key"], $_POST["vigenere_key"]);
                    }
                ?>
            </textarea>
        </form>
        <div class="footer">
            <span>V3923020_Zakaria Zidan Zamroni</span>
        </div>
    </div>
</body>
</html>

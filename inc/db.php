<?php

class Database {
  private $conn;

  public function connect($host, $username, $password, $dbname) {
    $this->conn = new mysqli($host, $username, $password, $dbname);
    if ($this->conn->connect_error) {
      die("Database connection failed: " . $this->conn->connect_error);
    }
  }

  public function get_categories() {
    $result = $this->conn->query("SELECT * FROM kategori");
    return $result;
  }

  public function get_category($id) {
    $stmt = $this->conn->prepare("SELECT namn, beskrivning FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_posts_by_category($id) {
    $stmt = $this->conn->prepare("SELECT * FROM inlägg WHERE kategori = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_user($id) {
    $stmt = $this->conn->prepare("SELECT * FROM användare WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_user_by_username($username) {
    $stmt = $this->conn->prepare("SELECT * FROM användare WHERE användarnamn = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_user_by_email($email) {
    $stmt = $this->conn->prepare("SELECT * FROM användare WHERE mejladress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_user_by_email_or_username($username_or_email) {
    $stmt = $this->conn->prepare("SELECT * FROM användare WHERE mejladress = ? OR användarnamn = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result= $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function get_image($id) {
    $stmt = $this->conn->prepare("SELECT * FROM bild WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function create_user($username, $email, $passwordhash) {
    $stmt = $this->conn->prepare("INSERT INTO användare(användarnamn, mejladress, lösenordhash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordhash);
    $stmt->execute();
    $stmt->close();
    $last_id = $this->conn->insert_id;
    return $last_id;
  }

  public function create_image($bildadress) {
    $stmt = $this->conn->prepare("INSERT INTO bild(bildadress) VALUES (?)");
    $stmt->bind_param("s", $bildadress);
    $stmt->execute();
    $stmt->close();
    $last_id = $this->conn->insert_id;
    return $last_id;
  }

  public function create_post($title, $contents, $creator, $category, $image=0) {
    if ($image != 0) {
      $stmt = $this->conn->prepare("INSERT INTO inlägg(titel, innehåll, skapare, kategori, bild) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("ssiii", $title, $contents, $creator, $category, $image);
      $stmt->execute();
      $stmt->close();
      $last_id = $this->conn->insert_id;
      return $last_id;
    } elseif ($image == 0) {
      $stmt = $this->conn->prepare("INSERT INTO inlägg(titel, innehåll, skapare, kategori) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssii", $title, $contents, $creator, $category);
      $stmt->execute();
      $stmt->close();
      $last_id = $this->conn->insert_id;
      return $last_id;
    }
  }
}

<?php

class StudentController
{

    private $conn;
    private $data_mahasiswa = [];
    private $middleware;


    public function __construct()
    {
        $studentStorage = new StudentStorage;
        $this->conn = $studentStorage->connection;
        $this->middleware = new AuthMiddleware;
    }

    public function authChecker(Request $request, Response $response)
    {
        return $this->middleware->authenticate($request, $response);
    }

    public function getAllStudents(Request $request, Response $response, bool $sendResponse = true)
    {
        if ($this->authChecker($request, $response)) {
            $sql = "SELECT * FROM mahasiswa";

            try {
                $result = $this->conn->prepare($sql);
                $result->execute();
                $this->data_mahasiswa = $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $response->send(404, "Error", $e->getMessage());
            }

            if ($sendResponse) {
                $response->send(200, "fetched", $this->data_mahasiswa);
                return $response;
            }
            return $response;
        }
    }

    public function getOneStudent(Request $request, Response $response)
    {

        if ($this->authChecker($request, $response)) {
            $params = $request->getParams();
            $nim = $params["nim"];

            if (!$nim) {
                $response->send(400, "Something Wrong", []);
                return $response;
            }

            $sql = "SELECT * FROM mahasiswa WHERE nim = '$nim'";

            try {
                $result = $this->conn->prepare($sql);
                $result->execute();
                $this->data_mahasiswa = $result->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $response->send(404, "Error", $e->getMessage());
            }

            $response->send(404, "fetched", [$this->data_mahasiswa]);
            return $response;
        }
    }

    public function addStudent(Request $request, Response $response)
    {

        if ($this->authChecker($request, $response)) {
            $body = $request->getBody();
            if (!$body) {
                $response->send(400, "Samting Wong", []);
                return $response;
            }

            $data = $request->getBody();
            $newNim = $data["nim"];
            $newName = $data["nama"];
            $newAlamat = $data["alamat"];
            $newJurusan = $data["jurusan"];
            $newSemester = $data["semester"];

            $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, alamat, semester) 
                VALUES ('$newNim', '$newName', '$newJurusan', '$newAlamat', '$newSemester')";

            try {
                $this->conn->exec($sql);
            } catch (PDOException $e) {
                $response->send(404, "Error", $e->getMessage());
            }
            $this->getAllStudents($request, $response, false);
            $response->send(201, "inserted", $this->data_mahasiswa);
            return $response;
        }
    }

    public function updateStudent(Request $request, Response $response)
    {

        if ($this->authChecker($request, $response)) {
            $params = $request->getParams();
            $nim = $params["nim"];
            if (!$nim) {
                $response->send(400, "Something Wrong", []);
                return $response;
            }

            $data = $request->getBody();
            $newName = $data["nama"];
            $newAlamat = $data["alamat"];
            $newJurusan = $data["jurusan"];
            $newSemester = $data["semester"];

            $sql = "UPDATE mahasiswa 
                SET nama = '$newName', jurusan = '$newJurusan', alamat = '$newAlamat', semester = '$newSemester' 
                WHERE nim = '$nim'";

            try {
                $res = $this->conn->exec($sql);
                if ($res === 0) {
                    $response->send(404, "No Matching Nim", []);
                    return $response;
                }
            } catch (PDOException $e) {
                $response->send(404, "Error", $e->getMessage());
            }

            $this->getAllStudents($request, $response, false);
            $response->send(200, "updated", $this->data_mahasiswa);
            return $response;
        }
    }

    public function deleteStudent(Request $request, Response $response)
    {

        if ($this->authChecker($request, $response)) {
            $params = $request->getParams();
            $nim = $params["nim"];
            if (!$nim) {
                $response->send(400, "Samting Wong", []);
                return $response;
            }

            $sql = "DELETE FROM mahasiswa WHERE nim = '$nim'";

            try {
                $res = $this->conn->exec($sql);
                if ($res === 0) {
                    $response->send(404, "No Matching Nim", []);
                    return $response;
                }
            } catch (PDOException $e) {
                $response->send(404, "Error", $e->getMessage());
            }

            $this->getAllStudents($request, $response, false);
            $response->send(200, "deleted", $this->data_mahasiswa);
            return $response;
        }
    }
}

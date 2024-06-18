<?php
class ApplicantsTable
{
    protected $link;

    public function __construct(mysqli $link)
    {
        $this->link = $link;
    }

    /**
     * Получить абуриентов
     * @return array
     */
    public function getApplicants()
    {
        $applicantsPerPage = 50;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sortField = isset($_GET['sortField']) ? $this->link->real_escape_string($_GET['sortField']) : '';
        $sortType = isset($_GET['sort']) ? $this->link->real_escape_string($_GET['sort']) : 'asc';
        $search = isset($_GET['search']) ? $this->link->real_escape_string($_GET['search']) : '';

        if (!in_array($sortType, ['asc', 'desc'])) {
            $sortType = 'asc';
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS name, lastname, groupNum, points FROM applicants";

        if ($search) {
            $query .= " WHERE name LIKE '%$search%' OR lastname LIKE '%$search%' OR groupNum LIKE '%$search%'";
        }
        if ($sortField) {
            $query .= " ORDER BY $sortField $sortType";
        }

        $offset = ($currentPage - 1) * $applicantsPerPage;
        $query .= " LIMIT $applicantsPerPage OFFSET $offset";

        $result = $this->link->query($query);

        $applicants = [];
        while ($row = $result->fetch_assoc()) {
            $applicants[] = $row;
        }

        $totalResult = $this->link->query("SELECT FOUND_ROWS() as total");
        $totalApplicants = $totalResult->fetch_assoc()['total'];
        $total_pages = ceil($totalApplicants / $applicantsPerPage);

        $resultFull = [
            'data' => $applicants,
            'total_pages' => $total_pages,
            'current_page' => $currentPage,
            'total_applicants' => $totalApplicants
        ];

        return $resultFull;
    }

    /**
     * Добавить абуриента
     * @param array $applicantData
     */
    private function validateFields(array $data, array $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Что-то пропущено $field");
            }
        }
    }

    private function executeQuery(string $query, string $types, array $params)
    {
        $stmt = $this->link->prepare($query);

        if (!$stmt) {
            throw new Exception("Датабаза ошибка [{$this->link->errno}] {$this->link->error}");
        }

        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Ошибка[{$stmt->errno}] {$stmt->error}");
        }

        $stmt->close();

        return $result;
    }

    public function addApplicant(array $applicantData)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $requiredFields = ['name', 'lastname', 'gender', 'groupNum', 'email', 'points', 'dateOfB'];
            $this->validateFields($applicantData, $requiredFields);

            $query = "INSERT INTO applicants (name, lastname, gender, groupNum, email, points, dateOfB) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $types = "sssssis";
            $params = [
                $applicantData['name'],
                $applicantData['lastname'],
                $applicantData['gender'],
                $applicantData['groupNum'],
                $applicantData['email'],
                $applicantData['points'],
                $applicantData['dateOfB']
            ];

            return $this->executeQuery($query, $types, $params);
        }

        return null;
    }

    public function editApplicant(array $applicantData)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $requiredFields = ['id', 'name', 'lastname', 'gender', 'groupNum', 'points', 'dateOfB'];
            $this->validateFields($applicantData, $requiredFields);

            $query = "UPDATE applicants SET name=?, lastname=?, gender=?, groupNum=?, points=?, dateOfB=? WHERE id=?";
            $types = "ssssisi";
            $params = [
                $applicantData['name'],
                $applicantData['lastname'],
                $applicantData['gender'],
                $applicantData['groupNum'],
                $applicantData['points'],
                $applicantData['dateOfB'],
                $applicantData['id']
            ];

            return $this->executeQuery($query, $types, $params);
        }

        return null;
    }
    // Может, ещё что-то добавлю.
}

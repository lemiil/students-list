<?php
class Validation
{
    protected $link;
    public function __construct(mysqli $link)
    {
        $this->link = $link;
    }
    /**
     * Валидация почты
     * @param string $email
     */
    public function checkEmail($email)
    {
        $emailCheckQuery = $this->link->prepare("SELECT COUNT(*) FROM applicants WHERE email = ?");
        $emailCheckQuery->bind_param("s", $email);
        $emailCheckQuery->execute();
        $emailCheckQuery->bind_result($emailCount);
        $emailCheckQuery->fetch();
        $emailCheckQuery->close();
        if ($emailCount > 0) {
            return true;
        } else return false;
    }
    /**
     * Проверка куки
     */


    // Может, ещё что-то добавлю.
}

<?php
$message = 'הוסף מילה חדשה למילון!';
if(isset($_POST['sendForm']))
{
    
    if(isset($_POST['wordName'], $_POST['translate'], $_POST['type']))
    {
        
        require_once 'include/config.php';
        
        if(!empty($_POST['wordName']) && !empty($_POST['wordName']) && !empty($_POST['wordName']))
        {
            $stmt = $mysqli->prepare("INSERT INTO Vocabulary (EnglishWord, Translate, Type) VALUES (?, ?, ?)");
            $stmt->bind_param('ssd', $word, $translate, $type);
            
            $word = $_POST['wordName'];
            $translate = $_POST['translate'];
            $type = $_POST['type'];
            
            $stmt->execute();
            
            $message .= "<br />רישום של ".mysqli_stmt_affected_rows($stmt)." רשומות חדשות בוצע בהצלחה";
            //printf("%d Row inserted.\n", mysqli_stmt_affected_rows($stmt));
            
        }
        
        
        
    }
    
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="description" content="Practice Vocabulary add word"/>
        <meta name="keywords" content="english,hebrew,study,vocabulay,words,statements"/>
        <meta name="author" content="Matan Omesi"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Vocabulary - Add WORD</title>
    </head>

    <body dir="rtl">
        <form method="POST" action="?do=add">
            <input type="text" name="wordName" placeholder="Enter Word" size="25"/>
            <input type="text" name="translate" placeholder="Enter translate" size="25"/>
            <select>
                <option value="0">ללא סוג</option>
                <option value="1">שם עצם</option>
                <option value="2">פועל</option>
                <option value="3">תיאור</option>
                <option value="4">מילת הוספה</option>
                <option value="5">מילת ניגוד</option>
                <option value="6">סיבה/תוצאה</option>
            </select>
            <input type="submit" name="sendForm" value="הוסף"/><br /><br />
            <?=$message?>
        </form>
    </body>

</html>
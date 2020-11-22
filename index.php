<?php
require_once 'include/config.php';
require_once 'include/functions.php';


$Types = array('ללא סוג','שם עצם','פועל','תיאור','מילת הוספה','מילת ניגוד','סיבה/תוצאה');
$TypesStr = '';
foreach($Types as $k => $v)
    $TypesStr .= '<option value="'.$k.'">'.$v.'</option>';

$Levels = array('ללא רמה','300 מילים חשובות','רמה בסיסית','רמה בינונית','רמת מתקדמים');
$LevelsStr = '';
foreach($Levels as $k => $v)
{
    $LevelsStr .= '<option value="'.$k.'" '.(($k == 4) ? 'selected="selected"' : '').'>'.$v.'</option>';
}
    //$LevelsStr .= '<option value="'.$k.'">'.$v.'</option>';

$title = '';
$message = 'הוסף מילה חדשה למילון!';
if(isset($_POST['sendForm']))
{
    
    if(isset($_POST['wordName'], $_POST['translate'], $_POST['type'], $_POST['level']))
    {
        
        //require_once 'include/config.php';
        
        if(!empty($_POST['wordName']) && !empty($_POST['wordName']) && !empty($_POST['wordName']) && !empty($_POST['level']))
        {
            $stmt = $mysqli->prepare("INSERT INTO Vocabulary (EnglishWord, Translate, Type,Level) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssdd', $word, $translate, $type, $level);
            
            $word = $_POST['wordName'];
            $translate = $_POST['translate'];
            $type = $_POST['type'];
            $level = $_POST['level'];
            
            $stmt->execute();
            
            $message .= "<br />רישום של ".mysqli_stmt_affected_rows($stmt)." רשומות חדשות בוצע בהצלחה";
            //printf("%d Row inserted.\n", mysqli_stmt_affected_rows($stmt));
            
        }
        else
            $message .= '<br />עליך למלא את השדות.';
        
        
        
    }
    else
        $message .= '<br />לא התקבלו שדות/';
    
}


//$mysqli$query = $mysqli->query("SELECT * FROM Vocabulary");




$results = '';

if(isset($_GET['game']) && $_GET['game'] == 'on')
{
    /*SELECT
     question_number, question, answer_a, answer_b, answer_c, answer_d, right_answer
FROM
     questions
ORDER BY
     rand()  
LIMIT
     5 ;*/    
     $title = 'שאלון אמריקאי';

     
    $getDATA = $mysqli->query("SELECT * FROM Vocabulary ORDER BY rand() LIMIT 200");
    if($getDATA->num_rows > 0)
    {
        
        
        
        $array = array();
        $list = array();
        while($Row = $getDATA->fetch_array(MYSQLI_ASSOC))
        {
            $strArr = explode(',', $Row['Translate']);
            $Row['options'] = array($strArr[rand(0, count($strArr)-1)]);
            $array[] = $Row['WID'];
            
            $list[] = $Row;
             /*$results .= <<<EOF
             <tr>
                <td>{$Row['WID']}</td>
                <td>{$Row['EnglishWord']}</td>
                <td>{$Row['Translate']}</td>
                <td>{$Row['Type']}</td>
                <td>{$Row['Level']}</td>
             </tr>
EOF;*/
        
        }
        //print_r($array);
        
        $str = "WID <> '".implode("' AND WID <> '", $array)."'";
        //echo $str;
        $getDATA2 = $mysqli->query("SELECT * FROM Vocabulary WHERE ".$str." ORDER BY rand() LIMIT 600");
        if($getDATA2->num_rows > 0)
        {
            //echo 'sadsa';
            $listIndex = 0;
            $index = 0;
            
            while($Row2 = $getDATA2->fetch_array(MYSQLI_ASSOC))
            {
                $strArr2 = explode(',', $Row2['Translate']);
                $list[$index/3]['options'][] = $strArr2[rand(0, count($strArr2)-1)];
                
                $index++;
                if($index%3 == 0)
                    $listIndex++;
                
            }
            //echo '<pre>'.print_r($list, true).'</pre>';
        }
        
        foreach($list as $item)
        {
            $options = '';
            $getFirst = $item['options'][0];
            shuffle($item['options']);
            foreach($item['options'] as $k => $op)
                $options .= '<input class="option_word_radio" type="radio" id="WID_'.$item['WID'].'_'.$k.'" name="WID_'.$item['WID'].'" value="'.$k.'" /><label for="WID_'.$item['WID'].'_'.$k.'" class="option_word" data-value="'.$k.'">'.$op.'</label><br />';
            
            $correctKey = array_search($getFirst, $item['options']);
            
            $results .= <<<EOF
             <tr>
                <td>{$item['WID']}</td>
                <td class="word en-word">{$item['EnglishWord']}</td>
                <td class="word he-word">
                <form>
                    {$options}
                    <input type="hidden" name="correctAnswer" value="{$correctKey}" />
                </form>
                </td>
                <td>{$Types[$item['Type']]}</td>
                <td>{$Levels[$item['Level']]}</td>
             </tr>
EOF;
        }
        
        
    }
}
else
{
    $title= 'מסך ראשי';
    
    $getDATA = $mysqli->query("SELECT * FROM Vocabulary");
    
    if($getDATA->num_rows > 0)
    {
        while($Row = $getDATA->fetch_array(MYSQLI_ASSOC))
        {
             $results .= <<<EOF
             <tr>
                <td>{$Row['WID']}</td>
                <td class="word en-word">{$Row['EnglishWord']}</td>
                <td class="word he-word">{$Row['Translate']}</td>
                <td>{$Types[$Row['Type']]}</td>
                <td>{$Levels[$Row['Level']]}</td>
             </tr>
EOF;
        
        }
    }    
}


/*
foreach($mysqli->query("SELECT * FROM Vocabulary") as $Row)
{
    echo $Row['EnglishWord'].' - '.$Row['Translate'].'<br .>';
}
*/

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="description" content="Practice Vocabulary"/>
        <meta name="keywords" content="english,hebrew,study,vocabulay,words,statements"/>
        <meta name="author" content="Matan Omesi"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>אוצר מילים</title>
        
        <link rel="stylesheet" href="include/style.css"/>
        
        <script type="text/javascript">
        

/* http://www.alistapart.com/articles/zebratables/ *//*
function removeClassName (elem, className) {
	elem.className = elem.className.replace(className, "").trim();
}

function addCSSClass (elem, className) {
	removeClassName (elem, className);
	elem.className = (elem.className + " " + className).trim();
}

String.prototype.trim = function() {
	return this.replace( /^\s+|\s+$/, "" );
}

function stripedTable() {
	if (document.getElementById && document.getElementsByTagName) {  
		var allTables = document.getElementsByTagName('table');
		if (!allTables) { return; }
		for (var i = 0; i < allTables.length; i++) {
			if (allTables[i].className.match(/[\w\s ]*scrollTable[\w\s ]*//*)) {
				var trs = allTables[i].getElementsByTagName("tr");
				for (var j = 0; j < trs.length; j++) {
					removeClassName(trs[j], 'alternateRow');
					addCSSClass(trs[j], 'normalRow');
				}
				for (var k = 0; k < trs.length; k += 2) {
					removeClassName(trs[k], 'normalRow');
					addCSSClass(trs[k], 'alternateRow');
				}
			}
		}
	}
}

window.onload = function() { stripedTable(); }
*/
    </script>
        
    </head>

    <body>
    
    <script type="text/javascript" src="include/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="include/game.js"></script>
    
        <div id="Form-Container">
            <form method="POST" action="?do=add">
            
            <table>
                <tr>
                    <td>מילה:</td>
                    <td><input type="text" name="wordName" placeholder="הקלד מילה" autocomplete="off" size="25"/></td>
                </tr>
                <tr>
                    <td>פירוש:</td>
                    <td><input type="text" name="translate" placeholder="הקלד פירוש" autocomplete="off" size="25"/></td>
                </tr>
                <tr>
                    <td>סוג:</td>
                    <td>
                        <select name="type">
                            <?=$TypesStr?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>רמה:</td>
                    <td>
                        <select name="level">
                            <?=$LevelsStr?>
                        </select>
                    </td>
                </tr>
            </table>
            
                
                <input type="submit" name="sendForm" value="הוסף"/><br /><br />
                <?=$message?>
            </form>
            
            <br />
            <a href="index.php">מסך ראשי</a>
            
            <h1>משחקים</h1>
            <ul>
            <li><a href="?game=on">אוצר מילים - שאלון אמריקאי</a></li>
            </ul>
        </div>
        <div id="List-Words-Container">
        <div id="Title"><?=$title?></div>
            <table id="List-Table" class="special scrollTable">
                <thead class="fixedHeader">
                    <tr class="alternateRow">
                        <th style="width: 10%;">#</th>
                        <th style="width: 25%;">מילה</th>
                        <th style="width: 30%;">פירוש</th>
                        <th style="width: 15%;">סוג</th>
                        <th style="width: 20%;">רמה</th>
                    </tr>
                </thead>
                <tbody class="scrollContent">
                    <?=$results?>
                </tbody>
            </table>
        
        </div>
        
        
        
    </body>

</html>
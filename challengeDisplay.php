<?php include "template.php";
/** @var $conn */

if (!authorisedAccess(false, true, true)) {
    header("Location:index.php");
}

if (isset($_GET["moduleID"])) {
    $challengeToLoad = $_GET["moduleID"];

} else {
    header("location:challengesList.php");
}
$sql = $conn->query("SELECT moduleID, challengeTitle, challengeText, PointsValue, HashedFlag FROM Challenges WHERE moduleID = " . $challengeToLoad . " ORDER BY ID DESC");
$result = $sql->fetch();
$moduleID = $result["moduleID"];
$title = $result["challengeTitle"];
$challengeText = $result["challengeText"];
$pointsValue = $result["PointsValue"];
$hashedFlag = $result["HashedFlag"];

?>
<title>Challenge Information</title>
<h1>Challenge - <?= $title ?></h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-1">Challenge Number</div>
        <div class="col-10">Challenge Description</div>
        <div class="col-1">Challenge Points</div>
    </div>
    <div class="row">
        <div class="col-1"><?= $title ?></div>
        <div class="col-10"><?= $challengeText ?></div>
        <div class="col-1"><?= $pointsValue ?></div>
    </div>
    <div class="row">
        <div class="col-12">
            <!--//<form action="moduleEdit.php?ModuleID=-->
            <?php //= $moduleToLoad ?><!--" method="post" enctype="multipart/form-data">-->

            <form action="challengeDisplay.php?moduleID=<?= $moduleID ?>" method="post" enctype="multipart/form-data">


                <p>Please enter the flag:</p>
                <label>
                    <input type="text" name="flag" class="form-control" required="required">
                </label></p>


                <input type="submit" name="formSubmit" value="Submit">
            </form>
        </div>
    </div>
</div>


</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEnteredFlag = sanitise_data($_POST['flag']);
//    $challengeToLoad = $_GET["moduleID"];
//    $flagList = $conn->query("SELECT HashedFlag, PointsValue, moduleID, challengeTitle, challengeText, PointsValue FROM Challenges WHERE moduleID = " . $challengeToLoad . "");
//
//    while ($flagData = $flagList->fetch()) {
    if (password_verify($userEnteredFlag, $hashedFlag)) {

        $user = $_SESSION["user_id"];
        $sql = "UPDATE Users SET Score = SCORE + '$pointsValue' WHERE ID='$user'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

//        $userInformation = $conn->query("SELECT Score FROM Users WHERE ID='$user'");
//        $userData = $userInformation->fetch();
//        $addedScore = $userData["Score"] += $pointsValue;
//        $sql1 = "UPDATE Users SET Score=? WHERE Username=?";
//        $stmt = $conn->prepare($sql1);
//        $stmt->execute([$addedScore, $user]);

        $_SESSION["flash_message"] = "<div class='bg-success'>Success!</div>";

    } else {
        $_SESSION["flash_message"] = "<div class='bg-danger'>Flag failed - Try again</div>";
    }
//    }
}
?>
</body>
</html>



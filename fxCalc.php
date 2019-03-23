<!DOCTYPE html>
<?php
//Import our FX Data Model class into the form page so that we can refer to it's variables and functions.
require_once( 'FxDataModel.php' );
$fxDataModel = new FxDataModel();
$fxCurrencies = $fxDataModel->getFxCurrencies();
$iniArray = $fxDataModel->getIniArray();


//Validation and the "magic" that allows us to not rely on the HTML file anymore.
if (array_key_exists($iniArray[FxDataModel::SOURCE_CURRENCY_KEY], $_POST)) {
    $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] = htmlspecialchars($_POST[$iniArray[FxDataModel::SOURCE_AMOUNT_KEY]]);
} else {
    $_POST[$iniArray[FxDataModel::SOURCE_AMOUNT_KEY]] = '';
    $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] = htmlspecialchars($_POST[$iniArray[FxDataModel::SOURCE_AMOUNT_KEY]]);
}
if (is_numeric($iniArray[FxDataModel::SOURCE_AMOUNT_KEY])) {
    $iniArray[FxDataModel::DEST_CUREENCY_KEY] = htmlspecialchars($_POST[$iniArray[FxDataModel::DEST_CUREENCY_KEY]]);
    $iniArray[FxDataModel::SOURCE_CURRENCY_KEY] = htmlspecialchars($_POST[$iniArray[FxDataModel::SOURCE_CURRENCY_KEY]]);
    $iniArray[FxDataModel::DEST_AMOUNT_KEY] = $fxDataModel->getFxRate($iniArray[FxDataModel::SOURCE_CURRENCY_KEY], $iniArray[FxDataModel::DEST_CUREENCY_KEY]) * $iniArray[FxDataModel::SOURCE_AMOUNT_KEY];
} else {
    $iniArray[FxDataModel::DEST_AMOUNT_KEY] = null;
    $iniArray[FxDataModel::DEST_CUREENCY_KEY] = $fxCurrencies[0];
    $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] = null;
    $iniArray[FxDataModel::SOURCE_CURRENCY_KEY] = $fxCurrencies[0];
}
?>

<!--Start of the HTML form!-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ryan's F/X Calculator</title>
        <link href="css/main.css" rel="stylesheet" type="text/css"/> <!--Link the CSS to the page.-->
    </head>

    <body>
        <header>
            <h1>Ryan's Super F/X Calculator</h1>
        </header>
        <br/>
        <main>
            <form name="fxCalc" action="fxCalc.php" method="post">

                <h3>Choose your source currency code and enter the amount as precise as you want it.</h3>
                <!--Drop down for source currency code selection.-->
                <select name="sourceCurrency">
                <!--<select name="<?php echo $iniArray[FxDataModel::SOURCE_CURRENCY_KEY] ?>">-->
                    <?php
//                Iterate through the array of currency codes to populate the drop down menu dynamically.
                    foreach ($fxCurrencies as $fxCurrency) {
                        ?>
                        <option value="<?php echo $fxCurrency ?>"

                                <?php
//                            Ensure that the currency code value selected by the user is sent to the form and still shown as the selected option post-submission.
                                if ($fxCurrency === $iniArray[FxDataModel::SOURCE_CURRENCY_KEY]) {
                                    ?>   
                                    selected
                                    <?php
                                }
                                ?>
                                <!--The command to actually print the list.-->
                                <?php echo $fxCurrency ?></option>
                                <?php
                            }
                            ?>
                </select>
                <!--Text field for the source currency amount. The value attribute is dynamically filled by PHP. Reminder: validation is performed at the top of the code.-->
                <input type="text" name="sourceAmount" value="<?php echo $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] ?>" class="textField"/>
                <!--<input type="text" name="<?php echo $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] ?>" value="<?php echo $iniArray[FxDataModel::SOURCE_AMOUNT_KEY] ?>" class="textField"/>-->
                <br/>

                <h3>Now choose your destination currency code and click CALCULATE to receive your result!</h3>
                <!--Drop down for destination currency code selection.-->
                <select name="destCurrency">
                <!--<select name="<?php echo $iniArray[FxDataModel::DEST_CUREENCY_KEY] ?>">-->
                    <?php
//                Iterate through the array of currency codes to populate the drop down menu dynamically.
                    foreach ($fxCurrencies as $fxCurrency) {
                        ?>
                        <option value="<?php echo $fxCurrency ?>"

                                <?php
//                            Ensure that the currency code value selected by the user is sent to the form and still shown as the selected option post-submission.
                                if ($fxCurrency === $iniArray[FxDataModel::DEST_CUREENCY_KEY]) {
                                    ?>
                                    selected
                                    <?php
                                }
                                ?>
                                <!--The command to actually print the list.-->
                                <?php echo $fxCurrency ?></option>

                        <?php
                    }
                    ?>
                </select>
                <!--Read only text field for the destination currency amount. The value attribute is dynamically filled by PHP.-->
                <input type="text" name="destAmount" disabled="disabled" value="<?php echo $iniArray[FxDataModel::DEST_AMOUNT_KEY] ?>" class="textField"/>
                <!--<input type="text" name="<?php echo $iniArray[FxDataModel::DEST_AMOUNT_KEY] ?>" disabled="disabled" value="<?php echo $iniArray[FxDataModel::DEST_AMOUNT_KEY] ?>" class="textField"/>-->

                <br/><br/>
                <!--Submit our form for calculation.-->
                <input type="submit" value="CALCULATE" class="button"/>
                <!--Clear all text fields, resets the drop down menus to display the 0 index value of the array.-->
                <input type="reset" value="RESET" onclick="window.location.href = 'fxCalc.php'" class="button"/>

            </form>
        </main>

        <br/>

        <footer>
            <p>Copyright (c) 2019 Ryan Lasher. Unauthorized copying of my student work is not the right thing to do, but be inspired by the way I designed my page and come up with your own creative implementation!</p>
        </footer>
    </body>
</html>

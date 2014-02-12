<?php
include '../Raynetcrm.php';

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instanceName = 'nazevVasiInstance '; //Zde vlozte nazev Vasi instance
    $userName = 'prihlasovaciEmail@email'; //Zde vlozte uzivatelske jmeno pro vytvareni zaznamu
    $password = 'prihlasovaciHeslo'; //Zde vlozte heslo pro uzivatele k vytvareni zaznamu

    $crm = new Raynetcrm($instanceName, $userName, $password);
    $data = array(
        'subject' => $_REQUEST['subject'],
        'priority' => $_REQUEST['priority'],
        'company' => $_REQUEST['company'],
        'firstName' => $_REQUEST['firstName'],
        'lastName' => $_REQUEST['lastName'],
        'source' => $_REQUEST['source'],
        'category' => $_REQUEST['category'],
        'note' => $_REQUEST['note'],
        'address' => array(
            'street' => $_REQUEST['street'],
            'city' => $_REQUEST['city'],
            'district' => $_REQUEST['district'],
            'postalCode' => $_REQUEST['postalCode'],
            'country' => $_REQUEST['country'],
            'businessRegion' => $_REQUEST['businessRegion']
        ),
        'contactInfo' => array(
            'firstPhone' => $_REQUEST['firstPhone'],
            'secondPhone' => $_REQUEST['secondPhone'],
            'firstPhoneType' => $_REQUEST['firstPhoneType'],
            'secondPhoneType' => $_REQUEST['secondPhoneType'],
            'email' => $_REQUEST['email'],
            'web' => $_REQUEST['web'],
            'facebook' => $_REQUEST['facebook'],
            'fax' => $_REQUEST['fax'],
            'otherContacts' => $_REQUEST['otherContacts']
        ),
        'notifyUserList' => array('emailpronotifikace@email'),
        'notifyMessage' => 'Tato zprava se zobrazi v CRM jako notifikace'
    );

    $result = $crm->insertLead($data);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">

    <title>RAYNET CRM API</title>
</head>
<body style="padding-bottom: 20px;">
    <div class="container"><div class="row"><div class="col-md-8">

        <h1>Vytvoření zájemce v RAYNET CRM</h1>

        <?php if ($result === true): ?>
            <div class="alert alert-success">Zájemce byl úspěšně vytvořen.</div>
        <?php elseif ($result === false):?>
            <div class="alert alert-danger">Nastala chyba při vytváření zájemce.</div>
        <?php endif; ?>

        <form role="form" method="post">
            <h2>Základní údaje</h2>

            <div class="form-group">
                <label for="subject">Předmět</label>
                <input class="form-control" type="text" name="subject" id="subject" />
            </div>

            <div class="form-group">
                <label for="priority">Priorita</label>
                <select class="form-control" name="priority" id="priority">
                    <option value="MINOR">Nízká</option>
                    <option value="DEFAULT">Normální</option>
                    <option value="CRITICAL">Vysoká</option>
                </select>
            </div>

            <div class="form-group">
                <label for="company">Firma</label>
                <input class="form-control" type="text" name="company" id="company" />
            </div>

            <div class="form-group">
                <label for="firstName">Jméno</label>
                <input class="form-control" type="text" name="firstName" id="firstName" />
            </div>

            <div class="form-group">
                <label for="lastName">Příjmení</label>
                <input class="form-control" type="text" name="lastName" id="lastName" />
            </div>

            <div class="form-group">
                <label for="source">Zdroj</label>
                <select class="form-control" name="source" id="source">
                    <option value="call centrum">call centrum</option>
                    <option value="webový formulář">webový formulář</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Kategorie</label>
                <select class="form-control" name="category" id="category">
                    <option value="automobilky">automobilky</option>
                    <option value="vývoj software">vývoj software</option>
                </select>
            </div>

            <div class="form-group">
                <label for="note">Poznámka</label>
                <textarea class="form-control" name="note" id="name"></textarea>
            </div>

            <h2>Adresa</h2>

            <div class="form-group">
                <label for="street">Ulice</label>
                <input class="form-control" type="text" name="street" id="street" />
            </div>

            <div class="form-group">
                <label for="city">Město</label>
                <input class="form-control" type="text" name="city" id="city" />
            </div>

            <div class="form-group">
                <label for="district">Kraj</label>
                <input class="form-control" type="text" name="district" id="district" />
            </div>

            <div class="form-group">
                <label for="postalCode">PSČ</label>
                <input class="form-control" type="text" name="postalCode" id="postalCode" />
            </div>

            <div class="form-group">
                <label for="country">Stát</label>
                <select class="form-control" name="country" id="country">
                    <option value="Česká republika">Česká republika</option>
                    <option value="Slovensko">Slovensko</option>
                </select>
            </div>

            <div class="form-group">
                <label for="businessRegion">Obchodní teritorium</label>
                <select class="form-control" name="businessRegion" id="businessRegion">
                    <option value="Moravskoslezský kraj">Moravskoslezský kraj</option>
                    <option value="Praha">Praha</option>
                </select>
            </div>

            <h2>Kontakty</h2>

            <div class="form-group">
                <label for="firstPhone">Tel.1</label>
                <input class="form-control" type="text" name="firstPhone" id="firstPhone" />
            </div>

            <div class="form-group">
                <label for="firstPhoneType">Tel.1 typ</label>
                <select class="form-control" name="firstPhoneType" id="firstPhoneType">
                    <option value="mobil">mobil</option>
                    <option value="pevná linka">pevná linka</option>
                </select>
            </div>

            <div class="form-group">
                <label for="secondPhone">Tel.2</label>
                <input class="form-control" type="text" name="secondPhone" id="secondPhone" />
            </div>

            <div class="form-group">
                <label for="secondPhoneType">Tel.2 typ</label>
                <select class="form-control" name="secondPhoneType" id="secondPhoneType">
                    <option value="mobil">mobil</option>
                    <option value="pevná linka">pevná linka</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input class="form-control" type="text" name="email" id="email" />
            </div>

            <div class="form-group">
                <label for="web">WWW</label>
                <input class="form-control" type="text" name="web" id="web" />
            </div>

            <div class="form-group">
                <label for="facebook">Facebook</label>
                <input class="form-control" type="text" name="facebook" id="facebook" />
            </div>

            <div class="form-group">
                <label for="fax">Fax</label>
                <input class="form-control" type="text" name="fax" id="fax" />
            </div>

            <div class="form-group">
                <label for="otherContacts">Ostatní kontakty</label>
                <textarea class="form-control" name="otherContacts" id="otherContacts"></textarea>
            </div>

            <button class="btn btn-primary">Vytvořit zájemce</button>
        </form>

    </div></div></div>
</body>
</html>
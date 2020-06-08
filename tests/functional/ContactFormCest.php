<?php

class ComentariosCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['comentarios/index']);
    }

    public function openComentariosView(\FunctionalTester $I)
    {
        $I->see('Comentarios', 'h1');
    }

    public function guestUser(\FunctionalTester $I)
    {
        $I->see('', 'th.action-column');
    }

    public function loggedUser(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\Usuarios::findIdentity(1));
        $I->amOnRoute('comentarios/index');
        $I->see('', 'th.action-column');
    }
}

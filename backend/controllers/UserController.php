namespace app\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
}

/*public function actionIndex()
{
    //return $this->render('xindex');
    return 'yyyyyyyyyyy';
    //return 'xxxxxxxxxx';
}*/
public function actions() {
    $actions = parent::actions();
    return json_encode($actions) ;
    return 'ddddddd';
}
<?php
/**
*    File        : backend/controllers/subjectsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

require_once("./models/subjects.php");

function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'])) 
    {
        $subject = getSubjectById($conn, $input['id']);
        echo json_encode($subject);
    } 
    else 
    {
        $subjects = getAllSubjects($conn);
        echo json_encode($subjects);
    }
}

function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    $existent_Subject = getStudentByName($conn, $input['name']);

    if($existent_Subject!=NULL)
    {
        http_response_code(400);
        echo json_encode(["error" => "Duplicated_subject"]);
    }
    else
    {
        $result = createSubject($conn, $input['name']);

        if ($result['inserted'] > 0) 
        {
            echo json_encode(["message" => "Materia creada correctamente"]);
        } 
        else 
        {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo crear"]);
        }
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    $result = updateSubject($conn, $input['id'], $input['name']);
    if ($result['updated'] > 0) 
    {
        echo json_encode(["message" => "Materia actualizada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    
    $result = deleteSubject($conn, $input['id']);                               //Llamo a la funcion eliminar
    if ($result['deleted'] > 0)                                                 //Pregunto si se llego a eliminar algo
    {
        echo json_encode(["message" => "Materia eliminada correctamente"]);
    } 
    else                                                                        //Si no se elimino nada, pregunto si fue por un error de eliminacion o porque habia otra condicion de por medio
    {
        if($result['error'] == "has_relations")                                 //Si el registro eliminar tiene "has_relations" significa que no se puede eliminar,
        {                                                                          //porque hay estudiantes anotados a esa materia
            http_response_code(409);
            echo json_encode(["error" => "has_relation"]);                      //por lo que devuelvo un error 409 a la consola y envio un echo json con un registro dentro
        }
        else                                                                //hubo un error en el proceso de eliminacion desconocido
        {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo eliminar"]);
        }
    }
}
?>
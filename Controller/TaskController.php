<?php


class TaskController extends Controller
{
    public function taskListAction($parameters)
    {
        $cur_page = (int)($parameters['page'] ?? 1);
        $sort_params = array();
        $tasks = Task::all();
        if (!empty($parameters['sort']) && in_array($parameters['sort'], array('email', 'username', 'done'))) {
            $sort_params['sort'] = $parameters['sort'];
            if (!empty($parameters['order']) && $parameters['order'] === 'desc') {
                $sort_params['order'] = 'desc';
                $tasks->sortByDesc($parameters['sort']);
            } else {
                $tasks->sortBy($parameters['sort']);
            }
        }
        $count = count($tasks);
        $last_page = ceil($count / 3);
        $cur_page = ($cur_page <= $last_page) ? $cur_page : $last_page;
        $offSet = (($cur_page - 1) * 3);
        $tasks = $tasks->slice($offSet, 3);

        $this->view('task_list',
            array(
                'home_active' => 'active',
                'cur_page' => $cur_page,
                'sort_params' => $sort_params,
                'last_page' => $last_page,
                'tasks' => $tasks,
            ));
    }

    public function editAction($parameters)
    {
        if(!IS_ADMIN){
            header("HTTP/1.1 401 Unauthorized");
            include("401.html");
            exit();
        }
        $form = $parameters['post'];
        $task = Task::find($parameters['id'] ?? $form['id']);
        if (is_array($form) && count($form) > 0) {
            $errors = Task::validate($form, true);
            if ($errors === '') {
                $task->update([
                    'username' => $form['username'],
                    'email' => $form['email'], 'description' => $form['description'],
                    'done' => $form['done'] === 'on',
                ]);
                 header('Location: /');
            } else {
                $this->view('task_edit_form',
                    array(
                        'error' => $errors,
                        'new_active' => 'active',
                        'task' => $task,
                    ));
            }
        } else {
            $this->view('task_edit_form',
                array(
                    'new_active' => 'active',
                    'task' => $task,
                ));
        }
    }

    public function createAction($parameters)
    {
        $form = $parameters['post'];

        if (is_array($form)) {
            $errors = Task::validate($form);
            if ($errors === '') {
                Task::create(['username' => $form['username'], 'email' => $form['email'], 'description' => $form['description']]);
            } else {
                $this->view('task_form',
                    array(
                        'new_active' => 'active',
                        'errors' => $errors,
                    ));
            }
        }
        $this->view('task_form',
            array(
                'new_active' => 'active',
            ));

    }

}

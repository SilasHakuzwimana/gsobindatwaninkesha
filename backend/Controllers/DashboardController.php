<?php

namespace Controllers;

use Models\User;
use Models\UserActivity;
use Models\Pathway;
use Models\Gallery;
use Models\SchoolDocument;
use Models\SubscribersModel;

class DashboardController extends BaseController
{
  public function overview()
  {
    // $this->AuthMiddleware::requireAdmin(); // optional but recommended

    return $this->json([
      'welcome' => 'Welcome back, Admin!',

      'totals' => [
        'users'       => User::countAll(),
        'pathways'    => Pathway::countAll(),
        'documents'   => SchoolDocument::countAll(),
        'gallery'     => Gallery::countAll(),
        'subscribers' => SubscribersModel::countAll(),
        'messages' => UserActivity::countMessages(),
      ],

      'user_statistics' => [
        'admins' => User::countByRole("admin"),
        'students' => User::countByRole('student'),
        'teachers' => User::countByRole('teacher'),
        'alumni'   => User::countByRole('alumni'),
        'guests'   => User::countByRole('guest'),
      ],

      'recent_activities' => UserActivity::recent(5)
    ]);
  }
}

<?php

namespace Controllers;

use Models\UserActivity;

class UserActivityController
{
  public function list(): array
  {
    try {
      $data = UserActivity::list($_GET);
      return [
        'success' => true,
        'data' => $data
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'data' => [],
        'error' => $e->getMessage()
      ];
    }
  }

  public function show(): array
  {
    try {
      $data = UserActivity::show($_GET['id']);
      return [
        'success' => true,
        'data' => $data
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'data' => [],
        'error' => $e->getMessage()
      ];
    }
  }

  public function analytics(): array
  {
    try {
      $data = UserActivity::analytics();
      return [
        'success' => true,
        'data' => $data
      ];
    } catch (\Exception $e) {
      return [
        'success' => false,
        'data' => [],
        'error' => $e->getMessage()
      ];
    }
  }
}
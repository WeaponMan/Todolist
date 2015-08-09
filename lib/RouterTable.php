<?php
define('ROUTE_PRIVATE', true);
define('ROUTE_PUBLIC', false);
define('ROUTE_ALL', 2);

$table = [
  ['/', 'system/login.php', ROUTE_PUBLIC],  
  ['/', 'system/systempage.php', ROUTE_PRIVATE],  
  ['/logout', 'system/logout.php', ROUTE_PRIVATE],  
  ['/registration', 'system/registration.php', ROUTE_PUBLIC],
  ['/password/forgotten', 'system/forgotten_password.php', ROUTE_PUBLIC],
    
  ['/email/old', 'system/old_email.php', ROUTE_PUBLIC],
  ['/email/new', 'system/new_email.php', ROUTE_PUBLIC],
    
  ['/password/change', 'system/change_password.php', ROUTE_PUBLIC],
  ['/settings', 'system/settings.php', ROUTE_PRIVATE],
    
  ['/admin/users', 'admin/users.php', ROUTE_PRIVATE],
  ['/admin/lists', 'admin/lists.php', ROUTE_PRIVATE],
  ['/admin/list/rm', 'admin/list_rm.php', ROUTE_PRIVATE],
    
  ['/javascript/enable', 'system/enable_javascript.php', ROUTE_ALL],  
  ['/javascript/disable', 'system/disable_javascript.php', ROUTE_ALL],
    
  ['/list', 'lists/list.php', ROUTE_PRIVATE],   
  ['/list/rm', 'lists/list_rm.php', ROUTE_PRIVATE], 
  ['/list/add', 'lists/list_add.php', ROUTE_PRIVATE], 
  ['/list/edit', 'lists/list_edit.php', ROUTE_PRIVATE], 
  ['/list/tag', 'lists/list_by_tag.php', ROUTE_PRIVATE],
    
  ['/list/members', 'lists/list_members.php', ROUTE_PRIVATE],   
  ['/list/member/add', 'lists/list_member_add.php', ROUTE_PRIVATE], 
  ['/list/member/depose', 'lists/list_member_depose.php', ROUTE_PRIVATE],  
  ['/list/member/promote', 'lists/list_member_promote.php', ROUTE_PRIVATE],  
  ['/list/member/rm', 'lists/list_member_rm.php', ROUTE_PRIVATE],  
      
  ['/task', 'tasks/task.php', ROUTE_PRIVATE],   
  ['/task/add', 'tasks/task_add.php', ROUTE_PRIVATE],   
  ['/task/edit', 'tasks/task_edit.php', ROUTE_PRIVATE],   
  ['/task/rm', 'tasks/task_rm.php', ROUTE_PRIVATE],   
  ['/task/done', 'tasks/task_done.php', ROUTE_PRIVATE],
  ['/task/undone', 'tasks/task_undone.php', ROUTE_PRIVATE],
    
  ['/task/assignment/add', 'tasks/assignment_add.php', ROUTE_PRIVATE],
  ['/task/assignment/rm', 'tasks/assignment_rm.php', ROUTE_PRIVATE], 
    
  ['/task/reply/add', 'replies/reply_add.php', ROUTE_PRIVATE],   
  ['/task/reply/edit', 'replies/reply_edit.php', ROUTE_PRIVATE],   
  ['/task/reply/rm', 'replies/reply_rm.php', ROUTE_PRIVATE],   
  ['/test', 'test/test.php', ROUTE_ALL],  
];
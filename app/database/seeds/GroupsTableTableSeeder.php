<?php

class GroupsTableTableSeeder extends Seeder {

	public function run(){
		Group::create(array(
			'slug' => 'admin',
			'title' => 'Администраторы',
			'dashboard' => 'admin'
		));
		Group::create(array(
			'slug' => 'moderator',
			'title' => 'Модераторы',
			'dashboard' => 'moderator'
		));
		Group::create(array(
			'slug' => 'admin-projects',
			'title' => 'Администратор проектов',
			'dashboard' => 'projects-administrator'
		));
		Group::create(array(
			'slug' => 'performer',
			'title' => 'Исполнитель',
			'dashboard' => 'performer'
		));
	}

}
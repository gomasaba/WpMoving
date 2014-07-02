<?php
namespace WpMoving\Model\Wp;

class Post extends App{

	protected $table = 'posts';

	protected $primaryKey = 'ID';

	const CREATED_AT = 'post_date';

	const UPDATED_AT = 'post_modified';


}
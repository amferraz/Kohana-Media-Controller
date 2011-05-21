<?php

class Media_Controller_Media extends Controller
{

	protected $config = NULL;
	
	public function before()
	{
		parent::before();
		
		$this->config = Kohana::config('media');
	}
	
	public function handle_request($action, $path, $extension, $use_fallback = TRUE)
	{
		$config_key = Inflector::plural($action);
		$action_method = 'action_' . $action;
		
		$file = $this->config[$config_key]['directory'] . $path;
		
		if ($this->find_file($file, $extension)) {
			$this->display_file($file, $extension);
		} elseif ($use_fallback === TRUE) {
			$this->$action_method(
				Request::current()->query('fallback'),
				FALSE
			);
		} else {
			$this->error();
		}
	}
	
	public function action_style($path, $use_fallback = TRUE)
	{
		$this->handle_request('style', $path, 'css', $use_fallback);
	}

	public function action_script($path, $use_fallback = TRUE)
	{
		$this->handle_request('script', $path, 'js', $use_fallback);
	}
	
	public function action_image($path, $use_fallback = TRUE)
	{
		$image = $this->config['images']['directory'] . $path;
		$extension = $this->find_image_extension($image);
		
		$this->handle_request('image', $path, $extension, $use_fallback);
	}
	
	protected function find_file($file, $extension)
	{
		return Kohana::find_file('media', $file, $extension);
	}

	protected function find_image_extension($file)
	{
		foreach ($this->config['images']['extensions'] as $extension) {
			if (Kohana::find_file('media', $file, $extension) !== FALSE) {
				return $extension;
			}
		}
		
		return FALSE;
	}

	protected function display_file($file, $ext)
	{
		$path = Kohana::find_file('media', $file, $ext);
		
		$this->response->headers(array(
			'Content-Type'		=> File::mime_by_ext($ext) . '; charset=utf-8',
			'Content-Length'	=> (string) File::size($path),
		));
		
		$this->response->body(File::content($path));
	}
	
	protected function error()
	{
		throw new Http_Exception_404('File :file not found.', array(
			':file'	=> $this->request->param('path', NULL),
		));
	}
	
}
<?php

class Media_Controller_Media extends Controller
{

	protected $config = NULL;
	
	public function before()
	{
		parent::before();
		
		$this->config = Kohana::config('media');
	}
	
	public function action_style($path, $file)
	{
		$style = $this->config['styles']['directory'] . $this->get_file_path($path, $file);
		
		if ($this->find_file($style, 'css')) {
			$this->display_file($style, 'css');
		} else {
			$this->error();
		}
	}

	public function action_script($path, $file)
	{
		$script = $this->config['scripts']['directory'] . $this->get_file_path($path, $file);

		if ($this->find_file($script, 'js')) {
			$this->display_file($script, 'js');
		} else {
			$this->error();
		}
	}
	
	public function action_image($path, $file)
	{
		$image = $this->config['images']['directory'] . $this->get_file_path($path, $file);
		$extension = $this->find_image_file($image);
		
		if ($extension !== FALSE) {
			$this->display_file($image, $extension);
		} else {
			$this->error();
		}
	}

	protected function get_file_path($path, $file)
	{
		$file_path = '';
		
		if (!empty($path)) {
			$file_path .= $path . '/';
		}
		
		return $file_path . $file;
	}
	
	protected function find_file($file, $ext)
	{
		return Kohana::find_file('media', $file, $ext);
	}

	protected function find_image_file($file)
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
		$file = $this->get_file_path(
			$this->request->param('path', NULL),
			$this->request->param('file', NULL)
		);
		
		throw new Http_Exception_404('File :file not found.', array(
			':file'	=> $file,
		));
	}
	
}
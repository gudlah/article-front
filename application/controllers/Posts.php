<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {
	public function index() {
		$this->load->view('posts/allposts', [
            'idMenu'    => 1,
            'judul'     => 'All Posts'
        ]);
	}

    public function edit($id) {
    	$this->load->view('posts/edit', [
            'idMenu'    => 1,
            'judul'     => 'Edit',
            'id'        => $id
        ]);
	}

    public function addNew() {
		$this->load->view('posts/addnew', [
            'idMenu'    => 2,
            'judul'     => 'Add New'
        ]);
	}

    public function preview() {
		$this->load->view('posts/preview', [
            'idMenu'    => 3,
            'judul'     => 'Preview'
        ]);
	}
}

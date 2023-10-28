<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
	public function index()
	{
        $this->db->from('kategori');
        $this->db->order_by('nama_kategori','ASC');
        $kategori = $this->db->get()->result_array();
        $data = array( 
            'kategori' => $kategori
        );
		$this->load->view('kategori', $data);
	}
    public function simpan(){
        $this->db->from('kategori');
		$this->db->where('nama_kategori', $this->input->post('nama_kategori'));
        $data = array(
            'nama_kategori' => $this->input->post('nama_kategori')
        );
        $this->db->insert('kategori',$data);
        redirect('kategori');
    }
    public function hapus($id){ 
        $where = array(
            'id_kategori' => $id
        );
        $this->db->delete('kategori',$where);
        redirect('kategori');
    }
    public function edit(){ 
        $where = array('id_kategori'   => $this->input->post('id_kategori'));
        $data = array('nama_kategori'      => $this->input->post('nama_kategori'));
        $this->db->update('kategori',$data,$where);
        redirect('kategori');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konten extends CI_Controller {
	public function index()
	{
        $this->db->from('kategori');
        $this->db->order_by('nama_kategori','ASC');
        $kategori = $this->db->get()->result_array();

        $this->db->from('konten a');
        $this->db->join('kategori b','a.id_kategori=b.id_kategori','left');
        $this->db->order_by('tanggal','DESC');
        $konten = $this->db->get()->result_array();
        $data = array(
            'kategori' => $kategori,
            'konten' => $konten
        );
		$this->load->view('konten', $data);
		
	}
    public function simpan(){
        $namafoto = date('YmdHis').'.jpg';
        $config['upload_path']      = 'upload/produk/';
        $config['max_size']         = 3000 * 1024; //3 * 1024 * 1024; //3mb; 0=unlimited
        $config['file_name']        = $namafoto;
        $config['allowed_types']    = '*';
        $this->load->library('upload', $config);
        if($_FILES['foto']['size'] >= 3000 * 1024){
            $this->session->set_flashdata('alert', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-check me-2"></i>Maaf, Upload foto dengan ukuran yang kurang dari 500kb!!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>'); 
            redirect('admin/konten');
        }elseif(!$this->upload->do_upload('foto')){
            $error = array('error' => $this->upload->display_errors());
        }else{
            $data = array('upload_data' => $this->upload->data());
        }
        $this->db->from('konten');
		$this->db->where('judul', $this->input->post('judul'));
        $data = array(
            'judul' => $this->input->post('judul'),
            'id_kategori' => $this->input->post('id_kategori'),
            'keterangan' => $this->input->post('keterangan'),
            'harga' => $this->input->post('harga'),
            'tanggal' => date('Y-m-d'),
            'foto' => $namafoto
        );
        $this->db->insert('konten',$data);
        redirect('konten');
    }
    public function hapus($id){ 
        $filename=FCPATH.'/upload/produk/'.$id;
        if(file_exists($filename)){
            unlink("./upload/produk/".$id);
        }
        $where = array(
            'foto' => $id
        );
        $this->db->delete('konten',$where);
        redirect('konten');
    }
    public function update(){
        $namafoto = $this->input->post('nama_foto');//diambil dari input type HIDDEN 'name="nama_foto"', dari views "konten"-->
        $config['upload_path']      = 'upload/produk/';
        $config['max_size']         = 3000 * 1024; //3 * 1024 * 1024; //3mb; 0=unlimited-->
        $config['file_name']        = $namafoto;
        $config['overwrite']        = true;
        $config['allowed_types']    = '*';
        $this->load->library('upload', $config);
        if($_FILES['foto']['size'] >= 3000 * 1024){//'foto' diambil dari input type text 'name="foto"', dari views "konten"-->
            redirect('admin/konten');
        }elseif(!$this->upload->do_upload('foto')){
            $error = array('error' => $this->upload->display_errors());
        }else{
            $data = array('upload_data' => $this->upload->data());
        }
        $data = array(
            'judul' => $this->input->post('judul'),
            'id_kategori' => $this->input->post('id_kategori'),
            'keterangan' => $this->input->post('keterangan'),
            'harga' => $this->input->post('harga'),
            'foto' => $namafoto
        );
        $where = array(
            'foto' => $this->input->post('nama_foto')
        );

        $this->db->update('konten',$data,$where);
        redirect('konten');
    }
}

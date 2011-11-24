<?php $to_load = $this->core_model->getParamFromURL ( 'to_load' );
//var_dump($to_load);
switch ( $to_load) {
	case 'top_frame' :
		$this->template ['load_top_frame'] = true;
		$this->load->vars ( $this->template );
		
		$back_to = $this->core_model->getParamFromURL ( 'back' );
		$back_to = base64_decode ( $back_to );
		$this->template ['back'] = $back_to;
		$this->load->vars ( $this->template );
	
	break;
	
	default :
		$back_to_encoded = $this->core_model->getParamFromURL ( 'goback' );
		$this->template ['back_to_encoded'] = $back_to_encoded;
		$go_to = $this->core_model->getParamFromURL ( 'goto' );
		$go_to = base64_decode ( $go_to );
		$this->template ['go_to'] = $go_to;
		$this->load->vars ( $this->template );
	break;
}
$this->load->vars ( $this->template );







<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

	public function index()
	{
		$this->load->view('header');
		$this->load->view('menu');
		$this->load->view('stats');
		$this->load->view('footer');
	}


	public function reports($site) {

		if (! isset($site)) {
			$site = 'all';
		}

		$data = array();
		$this->load->model('common');

		$data['total'] = $this->common->ataquestotales($site);
		$data['urls'] = $this->common->reporturls($site);			
		$data['ips'] = $this->common->reportips($site);			


                $this->load->view('header');
                $this->load->view('menu');
		$this->load->view('report', $data);
                $this->load->view('footer');
	}

	public function updategraph($site)
	{
		$this->load->model('common');
		// Creamos un array con los sites configurados
                $sites = $this->config->item('sites');
		echo $this->common->getattackspeminpersite($site, $sites);
	}

	public function graph()
	{	
		$data = array();
		// Creamos un array con los sites configurados
		$data['sites'] = $this->config->item('sites');
		$this->load->model('common');

		foreach ($data['sites'] as $site) {
			${'ataques_' . $site} = $this->common->getattackspertimepersite('1', "$site", $data['sites']);
			foreach (${'ataques_' . $site} as ${'ataque_' . $site}) {
				$data['array_ataques_' . $site][${'ataque_' . $site}['minuto']] = ${'ataque_' . $site}['ataque'];
			} 
		}

		$this->load->view('graph', $data);
	}
	
	public function table($tstimefrom, $tstimeto, $site,$uri)
	{
		
		$data = array();
                $this->load->model('common');
		$this->load->library('nativesession');
                // Creamos un array con los sites configurados
                $data['sites'] = $this->config->item('sites');
	
		if ($this->input->post('search')) {
			$timefrom = $this->input->post('timefrom');
			$timeto = $this->input->post('timeto');
			$site = $this->input->post('site');
			$uri = $this->input->post('uri');

			$tstimefrom = strtotime($timefrom);
			$tstimeto = strtotime($timeto);
			
			$this->nativesession->set('tstimefrom', "$tstimefrom");
			$this->nativesession->set('tstimeto', "$tstimeto");

			$this->nativesession->set('timefrom', "$timefrom");
			$this->nativesession->set('timeto', "$timeto");
			$this->nativesession->set('site', "$site");
//			$this->nativesession->set('uri', "$uri");

			header ("Location: /stats/table/$tstimefrom/$tstimeto/$site/$uri");
		} else {

			// si no esta definido los parametros calculamos los datos de los ultimos 10 min
			if (! isset($tstimefrom)) {
				$tstimefrom = date('U') - 600;
				$this->nativesession->set('tstimefrom', "$tstimefrom");
				$timefrom = date('Y/m/d H:i', $tstimefrom);
				$this->nativesession->set('timefrom',$timefrom);
			} else {
				$timefrom = date('Y/m/d H:i', $tstimefrom);
				$this->nativesession->set('timefrom',$timefrom);
			}
			if (! isset($tstimeto)) {
				$tstimeto = date('U');
				$this->nativesession->set('tstimeto', "$tstimeto");
				$timeto = date('Y/m/d H:i', $tstimeto);
				$this->nativesession->set('timeto',$timeto);
			} else {
				$timeto = date('Y/m/d H:i', $tstimeto);
				$this->nativesession->set('timeto',$timeto);
			}
			if (! isset($site)) {
				$site = 'telecinco';
				$this->nativesession->set('site', "$site");
			}

				
			$data['getattacksdetail'] = $this->common->getattacksdetail($tstimefrom, $tstimeto, $site, $data['sites'], $uri);

			$this->load->view('header');
			$this->load->view('menu');
			$this->load->view('table', $data);
			$this->load->view('footer');
		}
	}


	public function show($id) 
	{
                $data = array();
                $this->load->model('common'); // Cargamos el modelo comun

		// Consultamos a la db
		$data['getattackbyid'] = $this->common->getattackbyid($id);
	
		$this->load->view('header');
                $this->load->view('menu');
                $this->load->view('showattack', $data);
                $this->load->view('footer');
	}

	public function topip()
	{
                $data = array();
                $this->load->model('common'); // Cargamos el modelo comun

                // Consultamos a la db
                $data['gettopip'] = $this->common->gettopip();

                $this->load->view('header');
                $this->load->view('menu');
                $this->load->view('topip', $data);
                $this->load->view('footer');
	}

        public function topsites()
        {
                $data = array();
                $this->load->model('common'); // Cargamos el modelo comun

                // Consultamos a la db
                $data['gettopsites'] = $this->common->gettopsites();

                $this->load->view('header');
                $this->load->view('menu');
                $this->load->view('topsites', $data);
                $this->load->view('footer');
        }

        public function topurls()
        {
                $data = array();
                $this->load->model('common'); // Cargamos el modelo comun

                // Consultamos a la db
                $data['gettopurls'] = $this->common->gettopurls();

                $this->load->view('header');
                $this->load->view('menu');
                $this->load->view('topurls', $data);
                $this->load->view('footer');
        }

	public function whois($ip)
	{
		$data = array();
		$this->load->model('common');
		$data['whois'] = $this->common->whois($ip);
	
                $this->load->view('header');
                $this->load->view('menu');
                $this->load->view('whois', $data);
                $this->load->view('footer');
	}

	public function velocimetro($action)
	{
		$data = array();
		$this->load->model('common'); // Cargamos el modelo comun
		$data['getattackspermin'] = $this->common->getattackspermin();
		if ($action == 'refresh') {
			echo $data['getattackspermin'];
		} else {	
                	$this->load->view('header');
                	$this->load->view('menu');
			$this->load->view('velocimetro', $data);
			$this->load->view('footer');
		}
	}
}


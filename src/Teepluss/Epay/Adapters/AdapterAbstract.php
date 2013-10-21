<?php namespace Teepluss\Epay\Adapters;

abstract class AdapterAbstract implements AdapterInterface {

	/**
     * @var true|false
     */
	protected $_sandbox = false;

	/**
     * @var interface language
     */
	protected $_language = "EN";

	/**
     * @var currency
     */
	protected $_currency = "THB";

	/**
     * @var API gateway URL
     */
	protected $_gatewayUrl;

	/**
     * @var Success URL
     */
	protected $_successUrl;

	/**
     * @var Cancel URL
     */
	protected $_cancelUrl;

	/**
	 * @var Fail URL
	 */
	protected $_failUrl;

	/**
     * @var Background process URL
     */
	protected $_backendUrl;

	/**
     * @var Gateway account Account
     */
	protected $_merchantAccount;

	/**
     * @var Invoice ID
     */
	protected $_invoice;

	/**
     * @var Payment purpose
     */
	protected $_purpose;

	/**
     * @var Final amount paid
     */
	protected $_amount;

	/**
	 * @var Remark, Note
	 */
	protected $_remark;

	/**
	 * @var Client IP Address
	 */
	protected $_client_ip_address;

	/**
	 * @var Gateway status returned mapping
	 */
	protected $_statusReturned = array(
		'success' => array(
			'success', 'successes', 'succeeded',
			'complete', 'completed', 'accept'
		),
		'failed'  => array(
			'fail', 'failed', 'reject', 'rejected',
			'cancel', 'canceled', 'error'
		),
		'pending' => array(
			'pending', 'waiting', 'wait',
			'process', 'progress'
		)
	);

	/**
	 * Construct the adapter payment
	 *
	 * @access public
	 * @param  array $params (option)
	 * @return void
	 */
	public function __construct($params=array())
	{
		$this->initialize($params);
	}

	/**
	 * Initialize options
	 *
	 * @access public
	 * @param  arary (option)
	 * @return void
	 */
	public function initialize($params)
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				$method = "set".ucfirst($key);
				if (method_exists($this, $method)) {
					$this->$method($val);
				}
				else {
					$this->setAny($key, $val);
				}
			}
		}
	}

	/**
	 * Set gateway account
	 * Normally account is email
	 *
	 * @access public
	 * @param  mixed string|array
	 * @return object class (chaining)
	 */
	public function setMerchantAccount($val)
	{
		// some adpater you need to pass parameters more than email
		if (is_array($val))
		{
			$opts = $val;
			foreach ($opts as $key => $val)
			{
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method)) {
					$this->$method($val);
				}
				else {
					throw new Payment_Exception('Method "'.$key.'" is not match for the adapter.');
				}
			}
			return $this;
		}

		// normally this is an email
		$this->_merchantAccount = $val;
		return $this;
	}

	/**
	 * Get gateway account
	 *
	 * @access public
	 * @return string
	 */
	public function getMerchantAccount()
	{
		return $this->_merchantAccount;
	}

	/**
	 * Set gateway interface language
	 *
	 * @access public
	 * @param  string $val
	 * @return object class (chaining)
	 */
	public function setLanguage($val)
	{
		$this->_language = strtoupper($val);
		return $this;
	}

	/**
	 * Get language
	 *
	 * @access public
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->_language;
	}

	/**
	 * Set payment currency
	 *
	 * @access public
	 * @param  string $val
	 * @return object class (chaining)
	 */
	public function setCurrency($val)
	{
		$this->_currency = strtoupper($val);
		return $this;
	}

	/**
	 * Get payment currency
	 *
	 * @access public
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->_currency;
	}

	/**
	 * Set unique invoice ID
	 *
	 * @access public
	 * @param  string
	 * @return object class (chaining)
	 */
	public function setInvoice($val)
	{
		$this->_invoice = $val;
		return $this;
	}

	/**
	 * Get invoice ID
	 *
	 * @access public
	 * @return string
	 */
	public function getInvoice()
	{
		return $this->_invoice;
	}

	/**
	 * Set purpose of payment
	 *
	 * @access public
	 * @param  string (just a message)
	 * @return object class (chaining)
	 */
	public function setPurpose($val)
	{
		$this->_purpose = $val;
		return $this;
	}

	/**
	 * Get purpose of payment
	 *
	 * @access public
	 * @return string
	 */
	public function getPurpose()
	{
		return $this->_purpose;
	}

	/**
	 * Set amount payment
	 *
	 * @access public
	 * @param  integer (Money)
	 * @return object class (chaining)
	 */
	public function setAmount($val)
	{
		if (is_numeric($val)) {
			$this->_amount = $val;
			return $this;
		}
		throw new Payment_Exception('Amount must be integer.');
	}

	/**
	 * Get amount
	 *
	 * @access public
	 * @return integer
	 */
	public function getAmount()
	{
		return $this->_amount;
	}

	/**
	 * Set remark or note
	 *
	 * @access public
	 * @param  string
	 * @return object class (chaining)
	 */
	public function setRemark($val)
	{
		$this->_remark = $val;
		return $this;
	}

	/**
	 * Get remark
	 *
	 * @access public
	 * @return string
	 */
	public function getRemark()
	{
		return $this->_remark;
	}

	/**
	 * Set client IP address
	 *
	 * @access public
	 * @param  string $val
	 * @return object class (chaining)
	 */
	public function setClientIpAddress($val)
	{
		$this->_client_ip_address = $val;
		return $this;
	}

	/**
	 * Get client IP address
	 *
	 * @access public
	 * @return string IP
	 */
	public function getClientIpAddress()
	{
		if (!$this->_client_ip_address)
		{
			if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
 				$ip = $_SERVER["HTTP_CLIENT_IP"];
			}
			elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
 				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			else {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
			$this->_client_ip_address = $ip;
		}
		return $this->_client_ip_address;
	}

	/**
	 * Set success front URL
	 *
	 * @access public
	 * @param  string (URL)
	 * @return object class (chaining)
	 */
	public function setSuccessUrl($val)
	{
		$this->_successUrl = $this->_addQueryState($val, 'success');
		return $this;
	}

	/**
	 * Get success URL
	 *
	 * @access public
	 * @return string
	 */
	public function getSuccessUrl()
	{
		return $this->_successUrl;
	}

	/**
	 * Set cancel front URL
	 * Status cancel from gateway
	 * Support for some adapter
	 *
	 * @access public
	 * @param  string (URL)
	 * @return object class (chaining)
	 */
	public function setCancelUrl($val)
	{
		$this->_cancelUrl = $this->_addQueryState($val, 'cancel');
		return $this;
	}

	/**
	 * Get cancel URL
	 *
	 * @access public
	 * @return string
	 */
	public function getCancelUrl()
	{
		return $this->_cancelUrl;
	}

	/**
	 * Set fail front URL
	 * Status reject from gateway
	 * This method does not support for many gateway
	 *
	 * @access public
	 * @param  string (URL)
	 * @return object class (chaining)
	 */
	public function setFailUrl($val)
	{
		$this->_failUrl = $this->_addQueryState($val, 'fail');
		return $this;
	}

	/**
	 * Get fail URL
	 *
	 * @access public
	 * @return string
	 */
	public function getFailUrl()
	{
		return $this->_failUrl;
	}

	/**
	 * Set backend
	 * Background process server to server
	 *
	 * @access public
	 * @param  string (URL)
	 * @return object class (chaining)
	 */
	public function setBackendUrl($val)
	{
		$this->_backendUrl = $this->_addQueryState($val, 'backend');
		return $this;
	}

	/**
	 * Get backend URL
	 *
	 * @access public
	 * @return string
	 */
	public function getBackendUrl()
	{
		return $this->_backendUrl;
	}

	/**
	 * Set any var
	 *
	 * @access public
	 * @param  string $name
	 * @param  mixed $val
	 * @return object class (chaining)
	 */
	public function setAny($name, $val)
	{
		$private_name = "_".(string)$name;
		$this->{$private_name} = $val;
		return $this;
	}

	/**
	 * Get any var
	 *
	 * @access public
	 * @param  string $name
	 * @return mixed
	 */
	public function getAny($name)
	{
		$private_name = "_".(string)$name;
		if ($this->{$private_name}) {
			return $this->{$name};
		}
		return false;
	}

	/**
	 * State of success payment returned.
	 *
	 * @access public
	 * @return bool
	 */
	public function isSuccessPosted()
	{
		if (array_key_exists('state', $_GET)) {
			return (strcmp($_GET['state'], 'success') == 0);
		}
		return false;
	}

	/**
	 * State of canceled payment returned.
	 *
	 * @access public
	 * @return bool
	 */
	public function isCancelPosted()
	{
		if (array_key_exists('state', $_GET)) {
			return (strcmp($_GET['state'], 'cancel') == 0);
		}
		return false;
	}

	/**
	 * State of rejected payment returned.
	 * This state does not support for many gateway
	 *
	 * @access public
	 * @return bool
	 */
	public function isFailPosted()
	{
		if (array_key_exists('state', $_GET)) {
			return (strcmp($_GET['state'], 'fail') == 0);
		}
		return false;
	}

	/**
	 * State of backend post to server.
	 *
	 * @access public
	 * @return bool
	 */
	public function isBackendPosted()
	{
		if (array_key_exists('state', $_GET) && count($_POST) > 0) {
			return (strcmp($_GET['state'], 'backend') == 0);
		}
	}

	/**
	 * Add state to query URL
	 *
	 * @access private
	 * @param  string $url
	 * @param  string $state
	 * @return string
	 */
	private function _addQueryState($url, $state)
	{
		if (strpos($url, '?')) {
			$url .= "&state=".$state;
		}
		else {
			$url .= "?state=".$state;
		}
		return $url;
	}

	/**
	 * Building HTML Form
	 * Adapter use this to building a hidden form
	 *
	 * @access protected
	 * @param  array
	 * @return string HTML
	 */
	protected function _makeFormPayment($attrs=array(), $method = 'POST')
	{
		$hiddens = array();
		foreach ($attrs as $attr_key => $attr_val) {
			$hiddens[] = '<input type="hidden" name="'.$attr_key.'" value="'.$attr_val.'" />' . "\n";
		}
		$form = '
			<form method="'.$method.'" action="'.$this->_gatewayUrl.'" id="form-gateway">
				'.implode('', $hiddens).'
			</form>
		';
		return $form;
	}

	/**
	 * Make POST request via CURL
	 *
	 * @access protected
	 * @param  string $url
	 * @param  array
	 * @param  array
	 * @return array
	 */
	protected function _makeRequest($url, $data=array(), $curl_opts_extends=array())
	{
		$curl = curl_init();
		$data = http_build_query($data);

		$curl_opts = array(
		    CURLOPT_URL            => $url,
		    CURLOPT_POST           => true,
		    CURLOPT_POSTFIELDS     => $data,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_HEADER         => false,
		    CURLOPT_SSL_VERIFYPEER => false
		);

		// override or extend curl options
		if (count($curl_opts_extends) > 0) foreach ($curl_opts_extends as $key => $val) {
			$curl_opts[$key] = $val;
		}
		curl_setopt_array($curl, $curl_opts);

		// responded
		$response = curl_exec($curl);
		$status   = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return array(
			'status'   => ($status == 200) ? true : false,
			'response' => $response
		);
	}

	/**
	 * Map status returned from all gateway
	 * Because too much format return from gateway
	 *
	 * @access protected
	 * @param  string
	 * @return string
	 */
	protected function _mapStatusReturned($keyword)
	{
		$keyword = strtolower($keyword);
		$all_status = $this->_statusReturned;
		foreach ($all_status as $status => $group)
		{
			if (in_array($keyword, $group)) {
				return $status;
			}
		}
		return "other";
	}

	/**
	 * Change integer to real number format
	 *
	 * @access protected
	 * @param  integer
	 * @param  integer $decimals (default: 2)
	 * @return float
	 */
	protected function _decimals($int, $decimals=2)
	{
		return number_format($int, $decimals, '.', '');
	}

}

?>
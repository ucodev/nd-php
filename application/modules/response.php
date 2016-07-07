<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2016  Pedro A. Hortas (pah@ucodev.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 * ND PHP Framework (www.nd-php.org) - Contributor Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */

class UW_Response extends UW_Module {
	/* RFC2616 Section 10 */
	private $_code_name_desc = array(
		/* Informational 1xx */
		'100' => array('Continue', NULL),
		'101' => array('Switching Protocols', NULL),
		/* Successful 2xx */
		'200' => array('OK', NULL),
		'201' => array('Created', NULL),
		'202' => array('Accepted', NULL),
		'203' => array('Non-Authoritative Information', NULL),
		'204' => array('No Content', NULL),
		'205' => array('Reset Content', NULL),
		'206' => array('Partial Content', NULL),
		'300' => array('Multiple Choices', NULL),
		/* Redirection 3xx */
		'301' => array('Moved Permanently', NULL),
		'302' => array('Found', NULL),
		'303' => array('See Other', NULL),
		'304' => array('Not Modified', NULL),
		'305' => array('Use Proxy', NULL),
		'307' => array('Temporary Redirect', NULL),
		/* Client Error 4xx */
		'400' => array('Bad Request', 'The request could not be understood by the server due to malformed syntax.'),
		'401' => array('Unauthorized', 'The request requires user authentication.'),
		'402' => array('Payment Required', NULL),
		'403' => array('Forbidden', 'The server understood the request, but is refusing to fulfill it.'),
		'404' => array('Not Found', 'The server has not found anything matching the Request-URI.'),
		'405' => array('Method Not Allowed', 'The method specified in the Request-Line is not allowed for the resource identified by the Request-URI.'),
		'406' => array('Not Acceptable', 'The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request.'),
		'407' => array('Proxy Authentication Required', 'Client must first authenticate itself with the proxy.'),
		'408' => array('Request Timeout', 'The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time.'),
		'409' => array('Conflict', 'The request could not be completed due to a conflict with the current state of the resource.'),
		'410' => array('Gone', 'The requested resource is no longer available at the server and no forwarding address is known.'),
		'411' => array('Length Required', 'The server refuses to accept the request without a defined Content- Length.'),
		'412' => array('Precondition Failed', 'The precondition given in one or more of the request-header fields evaluated to false when it was tested on the server.'),
		'413' => array('Request Entity Too Large', 'The server is refusing to process a request because the request entity is larger than the server is willing or able to process.'),
		'414' => array('Request-URI Too Long', 'The server is refusing to service the request because the Request-URI is longer than the server is willing to interpret.'),
		'415' => array('Unsupported Media Type', 'The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method.'),
		'416' => array('Requested Range Not Satisfiable', 'A Range request-header field was present on the request but the server is unable to fulfill it.'),
		'417' => array('Expectation Failed', 'The expectation given in an Expect request-header field could not be met by this server.'),
		/* Server Error 5xx */
		'500' => array('Internal Server Error', 'The server encountered an unexpected condition which prevented it from fulfilling the request.'),
		'501' => array('Not Implemented', 'The server does not support the functionality required to fulfill the request.'),
		'502' => array('Bad Gateway', 'The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.'),
		'503' => array('Service Unavailable', 'The server is currently unable to handle the request due to a temporary overloading or maintenance of the server.'),
		'504' => array('Gateway Timeout', 'The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server specified by the URI, or some other auxiliary server it needed to access in attempting to complete the request.'),
		'505' => array('HTTP Version Not Supported', 'The server does not support, or refuses to support, the HTTP protocol version that was used in the request message.')
	);

	public function header($key, $value, $replace = true) {
		header($key . ': ' . $value, $replace);
	}

	public function code($code, $content = NULL, $charset = 'UTF-8', $template = true, $protocol = 'HTTP/1.1') {
		if ((intval($code) >= 400 && intval($code) <= 417) || (intval($code) >= 500 && intval($code) <= 505)) {
			header($protocol . ' ' . $code . ' ' . $this->_code_name_desc[$code][0]);
			if ($content !== NULL && !$template) die($content);
			$data = array();
			$data['config'] = array();
			$data['config']['charset'] = $charset;
			$data['view'] = array();
			$data['view']['content'] = $content;
			$data['view']['code'] = array();
			$data['view']['code']['number'] = $code;
			$data['view']['code']['name'] = $this->_code_name_desc[$code][0];
			$data['view']['code']['description'] = $this->_code_name_desc[$code][1];
			die($this->view->load('_templates/errors/status_code', $data, true));
		} else {
			header($protocol . ' ' . $code . ' ' . $this->_code_name_desc[$code][0]);
			if ($content !== NULL) echo($content);
		}
	}
}

<?php
if(!defined('PIWIK_CONFIG_TEST_INCLUDED'))
{
	require_once dirname(__FILE__)."/../../../tests/config_test.php";
}

class Test_Piwik_jsProxy extends UnitTestCase
{
	function test_piwik_js()
	{
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $this->getStaticSrvUrl() . '/js/');
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		$fullResponse = curl_exec($curlHandle);
		$responseInfo = curl_getinfo($curlHandle);
		curl_close($curlHandle);

		$this->assertEqual($responseInfo["http_code"], 200);

		$piwik_js = file_get_contents(PIWIK_DOCUMENT_ROOT . '/piwik.js');
		$this->assertEqual($fullResponse, $piwik_js);
	}

	function test_piwik_php()
	{
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $this->getStaticSrvUrl() . '/js/?idsite=1');
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		$fullResponse = curl_exec($curlHandle);
		$responseInfo = curl_getinfo($curlHandle);
		curl_close($curlHandle);

		$this->assertEqual($responseInfo["http_code"], 200);
		$this->assertEqual($fullResponse, base64_decode("R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="));
	}

	/**
	 * Helper methods
	 */
	private function getStaticSrvUrl()
	{
		$path = Piwik_Url::getCurrentScriptPath();
		if(substr($path, -7) == '/tests/')
		{
			$path = substr($path, 0, -7);
		}
		else if(substr($path, -18) == '/tests/core/Piwik/')
		{
			$path = substr($path, 0, -18);
		}
		else
		{
			throw new Exception('unsupported test path: ' . $path);
		}

		return "http://" . $_SERVER['HTTP_HOST'] .  $path;
	}
}

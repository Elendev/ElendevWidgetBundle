<?php
namespace Elendev\WidgetBundle\Annotation;
/**
 * @Annotation
 * @Target({"METHOD"})
 *
 */
class Widget {

	private $tag;
	private $priority = null;

	public function __construct(array $data) {
		
		if (isset($data['value'])) {
			$data['tag'] = $data['value'];
			unset($data['value']);
		}
		
		foreach ($data as $key => $value) {
			$method = 'set'.str_replace('_', '', $key);
			if (!method_exists($this, $method)) {
				throw new \BadMethodCallException(sprintf("Unknown property '%s' on annotation '%s'.", $key, get_class($this)));
			}
			$this->$method($value);
		}
	}

	public function getTag() {
		return $this->tag;
	}

	public function setTag($tag) {
		$this->tag = $tag;
	}

	public function getPriority() {
		return $this->priority;
	}

	public function setPriority($priority) {
		$this->priority = $priority;
	}

}

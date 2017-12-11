<?php

namespace Drupal\xbbcode\Plugin;

use Drupal\Core\Render\Markup;
use Drupal\xbbcode\Parser\Tree\TagElementInterface;
use Drupal\xbbcode\TagProcessResult;

/**
 * This is a tag that delegates processing to a Twig template.
 */
class TemplateTagPlugin extends TagPluginBase {

  /**
   * The twig environment.
   *
   * @var \Twig_Environment
   */
  protected $twig;

  /**
   * The serializable identifier of the template.
   *
   * (Either a template name or inline code.)
   *
   * @var string
   */
  protected $template;

  /**
   * Ephemeral reference to the template.
   *
   * @var \Twig_TemplateWrapper
   */
  private $templateWrapper;

  /**
   * TemplateTagPlugin constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Twig_Environment $twig
   * @param string|null $template
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              \Twig_Environment $twig,
                              $template = NULL) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->twig = $twig;
    $this->template = $template;
  }

  /**
   * Get the tag template.
   *
   * @return \Twig_TemplateWrapper
   *   The compiled template that should render this tag.
   */
  protected function getTemplate() {
    if (!isset($this->templateWrapper)) {
      $this->templateWrapper = $this->twig->load($this->template);
    }
    return $this->templateWrapper;
  }

  /**
   * {@inheritdoc}
   */
  public function doProcess(TagElementInterface $tag) {
    return new TagProcessResult(Markup::create($this->getTemplate()->render([
      'settings' => $this->settings,
      'tag' => $tag,
    ])));
  }

}

<?php

namespace MicroweberPackages\PhpQuery\Events;

use MicroweberPackages\PhpQuery\PhpQuery;

/**
 * Static namespace for phpQuery events.
 *
 * @author Tobiasz Cudnik
 */
abstract class PhpQueryEvents
{
    /**
     * Trigger a type of event on every matched element.
     *
     * @param \DOMNode|\MicroweberPackages\PhpQuery\PhpQueryObject|string $document
     * @param string $type
     * @param array $data
     * @param \DOMNode|null $node
     */
    public static function trigger($document, $type, $data = array(), $node = null)
    {
        $documentID = PhpQuery::getDocumentID($document);
        $namespace = null;
        if (strpos($type, '.') !== false) {
            list($name, $namespace) = explode('.', $type);
        } else {
            $name = $type;
        }
        if (!$node) {
            if (self::issetGlobal($documentID, $type)) {
                $pq = PhpQuery::getDocument($documentID);
                $pq->find('*')->add($pq->document)
                    ->trigger($type, $data);
            }
        } else {
            if (isset($data[0]) && $data[0] instanceof DOMEvent) {
                $event = $data[0];
                $event->relatedTarget = $event->target;
                $event->target = $node;
                $data = array_slice($data, 1);
            } else {
                $event = new DOMEvent(array(
                    'type' => $type,
                    'target' => $node,
                    'timeStamp' => time(),
                ));
            }
            $i = 0;
            while ($node) {
                PhpQuery::debug('Triggering '.($i ? 'bubbled ' : '')."event '{$type}' on "
                    ."node \n");
                $eventNode = self::getNode($documentID, $node);
                if ($eventNode) {
                    foreach ($eventNode->eventHandlers as $handler) {
                        PhpQuery::debug("Calling event handler\n");
                        $event->currentTarget = $node;
                        $params = array_merge(array($event), $data);
                        $return = PhpQuery::callbackRun($handler['callback'], $params);
                        if ($return === false) {
                            $event->bubbles = false;
                        }
                    }
                }
                // to_bubble or not to bubble...
                if (!$event->bubbles) {
                    break;
                }
                $node = $node->parentNode;
                $i++;
            }
        }
    }

    /**
     * Binds a handler to a particular event on a node.
     *
     * @param \DOMNode|\MicroweberPackages\PhpQuery\PhpQueryObject|string $document
     * @param \DOMNode $node
     * @param string $type
     * @param mixed $data
     * @param callable|null $callback
     */
    public static function add($document, $node, $type, $data, $callback = null)
    {
        PhpQuery::debug("Binding '$type' event");
        $documentID = PhpQuery::getDocumentID($document);
        $eventNode = self::getNode($documentID, $node);
        if (!$eventNode) {
            $eventNode = self::setNode($documentID, $node);
        }
        if (!isset($eventNode->eventHandlers)) {
            $eventNode->eventHandlers = array();
        }
        $eventNode->eventHandlers[] = array(
            'type' => $type,
            'callback' => $callback,
            'data' => $data,
        );
    }

    /**
     * Remove event handler(s).
     *
     * @param \DOMNode|\MicroweberPackages\PhpQuery\PhpQueryObject|string $document
     * @param \DOMNode $node
     * @param string|null $type
     * @param callable|null $callback
     */
    public static function remove($document, $node, $type = null, $callback = null)
    {
        $documentID = PhpQuery::getDocumentID($document);
        $eventNode = self::getNode($documentID, $node);
        if (is_object($eventNode) && isset($eventNode->eventHandlers)) {
            foreach ($eventNode->eventHandlers as $k => $handler) {
                if ($handler['callback'] == $callback) {
                    unset($eventNode->eventHandlers[$k]);
                }
            }
        }
    }

    protected static function getNode($documentID, $node)
    {
        if (!isset(PhpQuery::$documents[$documentID])) {
            return null;
        }
        foreach (PhpQuery::$documents[$documentID]->eventsNodes as $eventNode) {
            if ($node->isSameNode($eventNode)) {
                return $eventNode;
            }
        }
        return null;
    }

    protected static function setNode($documentID, $node)
    {
        if (!isset(PhpQuery::$documents[$documentID])) {
            return null;
        }
        PhpQuery::$documents[$documentID]->eventsNodes[] = $node;

        return PhpQuery::$documents[$documentID]->eventsNodes[
            count(PhpQuery::$documents[$documentID]->eventsNodes) - 1
        ];
    }

    protected static function issetGlobal($documentID, $type)
    {
        return isset(PhpQuery::$documents[$documentID]) ? in_array($type, PhpQuery::$documents[$documentID]->eventsGlobal) : false;
    }
}
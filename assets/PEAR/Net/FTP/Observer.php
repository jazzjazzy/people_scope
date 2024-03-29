<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Net_FTP observer.
 *
 * This class implements the Observer part of a Subject-Observer
 * design pattern. It listens to the events sent by a Net_FTP instance.
 * This module had many influences from the Log_observer code.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Networking
 * @package    FTP
 * @author     Tobias Schlitt <toby@php.net>
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Chuck Hagenbuch <chuck@horde.org>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: Observer.php,v 1.2 2005/02/23 12:12:23 toby Exp $
 * @link       http://pear.php.net/package/Net_FTP
 * @since      File available since Release 0.0.1
 */

/**
 * This class implements the Observer part of a Subject-Observer
 * design pattern. It listens to the events sent by a Net_FTP instance.
 * This module had many influences from the Log_observer code.
 *
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @category   Networking
 * @package    FTP
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Chuck Hagenbuch <chuck@horde.org>
 * @author     Tobias Schlitt <toby@php.net>
 * @copyright  1997-2005 The PHP Group
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Net_FTP
 * @since      1.3.0.0
 * @access     public
 *
 * @example    observer_upload.php          An example of Net_FTP_Observer implementation.
 */
class Net_FTP_Observer
{
    /**
     * Instance-specific unique identification number.
     *
     * @var        integer
     * @since      1.3.0
     * @access     private
     */
    var $_id;

    /**
     * Creates a new basic Net_FTP_Observer instance.
     *
     * @since      1.3.0
     * @access     public
     */
    function Net_FTP_Observer()
    {
        $this->_id = md5(microtime());
    }

    /**
     * Returns the listener's identifier
     *
     * @return     string
     * @since      1.3.0
     * @access     public
     */
    function getId()
    {
        return $this->_id;
    }

    /**
     * This is a stub method to make sure that Net_FTP_Observer classes do
     * something when they are notified of a message.  The default behavior
     * is to just do nothing.
     * You should override this method.
     *
     * @param      mixed     $event         A hash describing the net event.
     *
     * @since      1.3.0
     * @access     public
     */
    function notify($event)
    {
        return;
    }
}
?>

<?php
/**
 * Stack must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models;

interface StackInterface
{
    public function flushMessages();
    public function getAllMessages();
    public function getDebugMessages();
    public function getOkayMessages();
    public function getErrorMessages();
}
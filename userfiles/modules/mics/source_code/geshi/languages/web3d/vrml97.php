<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/web3d/vrml97.php
 *   Author: Nigel McNie
 *   E-mail: nigel@geshi.org
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 *  This file is part of GeSHi.
 *
 *  GeSHi is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  GeSHi is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with GeSHi; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * You can view a copy of the GNU GPL in the COPYING file that comes
 * with GeSHi, in the docs/ directory.
 *
 * @package    geshi
 * @subpackage lang
 * @author     Nigel McNie <nigel@geshi.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2004 - 2006 Nigel McNie
 * @version    1.1.2alpha3
 *
 */

/**#@+
 * @access private
 */

function geshi_web3d_vrml97 (&$context)
{
    $context->addChild('comment');
    $context->addChild('string');
    
    // Nodes
    $context->addKeywordGroup(array(
        // sensors
        'CylinderSensor', 'PlaneSensor', 'ProximitySensor', 'SphereSensor',  
        'TimeSensor', 'TouchSensor', 'VisibilitySensor',
        // interpolators 
        'ColorInterpolator', 'CoordinateInterpolator', 'NormalInterpolator',
        'OrientationInterpolator', 'PositionInterpolator', 'ScalarInterpolator',
        // grouping nodes
        'Anchor', 'Billboard', 'Collision', 'Group', 'LOD', 'Switch', 'Transform',
        // bindables
        'Background', 'Fog', 'NavigationInfo', 'Viewpoint',
        // lights
        'DirectionalLight', 'PointLight', 'SpotLight',
        // shape and geometry 
        'Box', 'Cone', 'Coordinate', 'Cylinder', 'ElevationGrid', 'Extrusion',
        'IndexedFaceSet', 'IndexedLineSet', 'PointSet', 'Shape', 'Sphere', 'Text', 
        // appearance 
        'Appearance', 'Color', 'FontStyle', 'Material', 
        'Normal', 'TextureCoordinate', 'TextureTransform',
        // textures 
        'ImageTexture', 'MovieTexture', 'PixelTexture', 
        // sound
        'AudioClip', 'Sound', 
        // other
        'Inline', 'Script', 'WorldInfo'
    ), 'node', true, 'http://www.web3d.org/x3d/specifications/vrml/ISO-IEC-14772-IS-VRML97'
        . 'WithAmendment1/part1/nodesRef.html#{FNAME}');

    // Fields of nodes
    $context->addKeywordGroup(array(
        // A
        'addChildren', 'ambientIntensity', 'appearance', 'attenuation', 'autoOffset', 
        'avatarSize', 'axisOfRotation',
        // B 
        'backUrl', 'bboxCenter', 'bboxSize', 'beamWidth', 'beginCap', 
        'bindTime', 'bottom', 'bottomRadius', 'bottomUrl',
        // C
        'ccw', 'center', 'children', 'choice', 'collide', 
        'collideTime', 'color', 'colorIndex', 'colorPerVertex', 'convex', 
        'coord', 'coordIndex', 'creaseAngle', 'crossSection', 'cutOffAngle', 
        'cycleInterval', 'cycleTime', 
        // D
        'description', 'diffuseColor', 'direction', 'directOutput', 
        'diskAngle', 'duration_changed', 
        // E
        'emissiveColor', 'enabled', 'endCap', 'enterTime', 'exitTime', 
        // F
        'family', 'fieldOfView', 'fogType', 'fontStyle', 'fraction_changed', 
        'frontUrl', 
        // G
        'geometry', 'groundAngle', 'groundColor',
        // H 
        'headlight', 'height', 'hitNormal_changed', 'hitPoint_changed', 'horizontal',
        // I
        'image', 'info', 'intensity', 'isActive', 'isBound', 'isOver',
        // J
        'jump', 'justify', 
        // K
        'key', 'keyValue', 
        // L  
        'language', 'leftUrl', 'leftToRight','length', 'level', 
        'location', 'loop', 
        // M
        'material', 'maxAngle', 'maxBack', 'maxExtent', 'maxFront', 
        'maxPosition', 'minAngle', 'minBack', 'minFront', 'minPosition', 
        'mustEvaluate', 
        // N
        'normal', 'normalIndex', 'normalPerVertex', 
        // O
        'offset', 'on', 'orientation', 'orientation_changed', 
        // P
        'parameter', 'pitch', 'point', 'position', 'position_changed', 
        'priority', 'proxy', 
        // Q
        
        // R
        'radius', 'range', 'removeChildren', 'repeatS', 'repeatT', 
        'rightUrl', 'rotation', 'rotation_changed',
        // S
        'scale', 'scaleOrientation', 'set_bind', 'set_colorIndex', 'set_coordIndex', 
        'set_crossSection', 'set_fraction', 'set_height', 'set_normalIndex', 'set_orientation', 
        'set_spine', 'set_scale', 'set_texCoordIndex', 'shininess', 'side', 
        'size', 'skyAngle', 'skyColor', 'solid', 'source', 
        'spacing', 'spatialization', 'specularColor', 'speed', 'spine', 
        'startTime', 'stopTime', 'string', 'style', 
        // T
        'texCoord', 'texCoordIndex', 'texture', 'textureTransform', 'time', 
        'title', 'top', 'topUrl', 'topToBottom', 'touchTime', 
        'trackPoint_changed', 'translation', 'translation_changed', 'transparency', 'type',
        // U
        'url', 
        // V
        'value_changed', 'vector', 'visibilityLimit', 'visibilityRange', 
        // W
        'whichChoice', 
        // X
        'xDimension', 'xSpacing', 
        // Y
        
        // Z
        'zDimension', 'zSpacing' 
    ), 'field', true);
    
    // Keywords
    $context->addKeywordGroup(array(
        'DEF', 'USE', 'IS', 'PROTO', 'EXTERNPROTO', 'TO', 'ROUTE',
        'TRUE', 'FALSE', 'NULL',
    ), 'keyword', true);
    
    // Field access types
    $context->addKeywordGroup(array(
        'eventIn', 'eventOut', 'exposedField', 'field', 
    ), 'fieldaccess', true);
    
    // Field data types
    $context->addKeywordGroup(array(
        'SFBool', 'SFColor', 'SFFloat', 'SFImage', 'SFInt32', 'SFNode',
        'SFRotation', 'SFString', 'SFTime', 'SFVec2f', 'SFVec3f',
        'MFColor', 'MFFloat', 'MFInt32', 'MFNode',
        'MFRotation', 'MFString', 'MFTime', 'MFVec2f', 'MFVec3f',
    ), 'fieldtype', true);

    $context->addSymbolGroup(array(
        '{', '}'
    ), 'nodesymbol');
    
    $context->addSymbolGroup(array(
        '[', ']'
    ), 'arraysymbol');

    $context->useStandardIntegers();
    $context->useStandardDoubles();
}

function geshi_web3d_vrml97_comment (&$context)
{
    $context->addDelimiters('#', "\n");
    $context->parseDelimiters(GESHI_CHILD_PARSE_LEFT);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_web3d_vrml97_string (&$context)
{
    $context->addDelimiters('"', '"');
    $context->addChildLanguage('javascript/javascript',
        array('REGEX#javascript:#', 'REGEX#ecmascript:#', 'REGEX#vrmlscript:#'), '"',
        false, GESHI_CHILD_PARSE_LEFT);
}

/**#@-*/

?>
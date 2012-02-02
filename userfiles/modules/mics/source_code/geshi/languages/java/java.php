<?php
/**
 * GeSHi - Generic Syntax Highlighter
 * <pre>
 *   File:   geshi/languages/java/java.php
 *   Author: Tim Wright
 *   E-mail: tim.w@clear.net.nz
 * </pre>
 * 
 * For information on how to use GeSHi, please consult the documentation
 * found in the docs/ directory, or online at http://geshi.org/docs/
 * 
 * This program is part of GeSHi.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA
 *
 * @package    geshi
 * @subpackage lang
 * @author     Tim Wright <tim.w@clear.net.nz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 2005 - 2006 Tim Wright, Nigel McNie
 * @version    1.1.2alpha3
 *
 */
 
 /**#@+
 * @access private
 */
 
/** Get the GeSHiSingleCharContext class */
require_once GESHI_CLASSES_ROOT . 'class.geshisinglecharcontext.php';

function geshi_java_java (&$context)
{
    // Children of java context
    $context->addChild('double_string', 'string');
    $context->addChild('single_string', 'singlechar');
    $context->addChild('single_comment');
    $context->addChild('multi_comment');
    // Doxygen is used for highlighting javadoc comments
    $context->addChildLanguage('doxygen/doxygen', '/**', '*/', false, GESHI_CHILD_PARSE_BOTH);
    
    // Keyword groups
    
    // Keywords
    $context->addKeywordGroup(array(
            'abstract', 'assert', 'break', 'case', 'catch',
            'class', 'const', 'continue', 'default', 'do',
            'else', 'enum', 'extends', 'final', 'finally', 'for',
            'goto', 'if', 'implements', 'import', 'instanceof',
            'interface', 'native', 'new', 'package', 'private',
            'protected', 'public', 'return', 'static', 'strictfp',
            'super', 'switch', 'synchronized', 'this', 'throw', 'throws',
            'transient', 'try', 'volatile', 'while' 
    ), 'keyword', true);
    
    // Data Types
    $context->addKeywordGroup(array(
            'byte', 'short', 'int', 'long', 'float', 'double',
            'char', 'boolean', 'void'
    ), 'dtype', true);
    
 	//java.applet
	$context->addKeywordGroup(array(
			'Applet', 'AppletContext', 'AppletStub', 
			'AudioClip'),

		'java/java/java/applet',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/applet/{FNAME}.html'
	);


	//java.awt
	$context->addKeywordGroup(array(
			'AWTError', 'AWTEvent', 'AWTEventMulticaster', 
			'AWTException', 'AWTKeyStroke', 'AWTPermission', 
			'ActiveEvent', 'Adjustable', 'AlphaComposite', 
			'BasicStroke', 'BorderLayout', 'BufferCapabilities', 
			'BufferCapabilities.FlipContents', 'Button', 'Canvas', 
			'CardLayout', 'Checkbox', 'CheckboxGroup', 
			'CheckboxMenuItem', 'Choice', 'Color', 
			'Component', 'ComponentOrientation', 'Composite', 
			'CompositeContext', 'Container', 'ContainerOrderFocusTraversalPolicy', 
			'Cursor', 'DefaultFocusTraversalPolicy', 'DefaultKeyboardFocusManager', 
			'Dialog', 'Dimension', 'DisplayMode', 
			'Event', 'EventQueue', 'FileDialog', 
			'FlowLayout', 'FocusTraversalPolicy', 'Font', 
			'FontFormatException', 'FontMetrics', 'Frame', 
			'GradientPaint', 'Graphics', 'Graphics2D', 
			'GraphicsConfigTemplate', 'GraphicsConfiguration', 'GraphicsDevice', 
			'GraphicsEnvironment', 'GridBagConstraints', 'GridBagLayout', 
			'GridLayout', 'HeadlessException', 'IllegalComponentStateException', 
			'Image', 'ImageCapabilities', 'Insets', 
			'ItemSelectable', 'JobAttributes', 'JobAttributes.DefaultSelectionType', 
			'JobAttributes.DestinationType', 'JobAttributes.DialogType', 'JobAttributes.MultipleDocumentHandlingType', 
			'JobAttributes.SidesType', 'KeyEventDispatcher', 'KeyEventPostProcessor', 
			'KeyboardFocusManager', 'Label', 'LayoutManager', 
			'LayoutManager2', 'List', 'MediaTracker', 
			'Menu', 'MenuBar', 'MenuComponent', 
			'MenuContainer', 'MenuItem', 'MenuShortcut', 
			'MouseInfo', 'PageAttributes', 'PageAttributes.ColorType', 
			'PageAttributes.MediaType', 'PageAttributes.OrientationRequestedType', 'PageAttributes.OriginType', 
			'PageAttributes.PrintQualityType', 'Paint', 'PaintContext', 
			'Panel', 'Point', 'PointerInfo', 
			'Polygon', 'PopupMenu', 'PrintGraphics', 
			'PrintJob', 'Rectangle', 'RenderingHints', 
			'RenderingHints.Key', 'Robot', 'ScrollPane', 
			'ScrollPaneAdjustable', 'Scrollbar', 'Shape', 
			'Stroke', 'SystemColor', 'TextArea', 
			'TextComponent', 'TextField', 'TexturePaint', 
			'Toolkit', 'Transparency', 'Window'),

		'java/java/java/awt',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/{FNAME}.html'
	);


	//java.awt.color
	$context->addKeywordGroup(array(
			'CMMException', 'ColorSpace', 'ICC_ColorSpace', 
			'ICC_Profile', 'ICC_ProfileGray', 'ICC_ProfileRGB', 
			'ProfileDataException'),

		'java/java/java/awt/color',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/color/{FNAME}.html'
	);


	//java.awt.datatransfer
	$context->addKeywordGroup(array(
			'Clipboard', 'ClipboardOwner', 'DataFlavor', 
			'FlavorEvent', 'FlavorListener', 'FlavorMap', 
			'FlavorTable', 'MimeTypeParseException', 'StringSelection', 
			'SystemFlavorMap', 'Transferable', 'UnsupportedFlavorException'),

		'java/java/java/awt/datatransfer',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/datatransfer/{FNAME}.html'
	);


	//java.awt.dnd
	$context->addKeywordGroup(array(
			'Autoscroll', 'DnDConstants', 'DragGestureEvent', 
			'DragGestureListener', 'DragGestureRecognizer', 'DragSource', 
			'DragSourceAdapter', 'DragSourceContext', 'DragSourceDragEvent', 
			'DragSourceDropEvent', 'DragSourceEvent', 'DragSourceListener', 
			'DragSourceMotionListener', 'DropTarget', 'DropTarget.DropTargetAutoScroller', 
			'DropTargetAdapter', 'DropTargetContext', 'DropTargetDragEvent', 
			'DropTargetDropEvent', 'DropTargetEvent', 'DropTargetListener', 
			'InvalidDnDOperationException', 'MouseDragGestureRecognizer'),

		'java/java/java/awt/dnd',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/dnd/{FNAME}.html'
	);


	//java.awt.event
	$context->addKeywordGroup(array(
			'AWTEventListener', 'AWTEventListenerProxy', 'ActionEvent', 
			'ActionListener', 'AdjustmentEvent', 'AdjustmentListener', 
			'ComponentAdapter', 'ComponentEvent', 'ComponentListener', 
			'ContainerAdapter', 'ContainerEvent', 'ContainerListener', 
			'FocusAdapter', 'FocusEvent', 'FocusListener', 
			'HierarchyBoundsAdapter', 'HierarchyBoundsListener', 'HierarchyEvent', 
			'HierarchyListener', 'InputEvent', 'InputMethodEvent', 
			'InputMethodListener', 'InvocationEvent', 'ItemEvent', 
			'ItemListener', 'KeyAdapter', 'KeyEvent', 
			'KeyListener', 'MouseAdapter', 'MouseEvent', 
			'MouseListener', 'MouseMotionAdapter', 'MouseMotionListener', 
			'MouseWheelEvent', 'MouseWheelListener', 'PaintEvent', 
			'TextEvent', 'TextListener', 'WindowAdapter', 
			'WindowEvent', 'WindowFocusListener', 'WindowListener', 
			'WindowStateListener'),

		'java/java/java/awt/event',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/event/{FNAME}.html'
	);


	//java.awt.font 
	$context->addKeywordGroup(array(
			'FontRenderContext', 'GlyphJustificationInfo', 'GlyphMetrics', 
			'GlyphVector', 'GraphicAttribute', 'ImageGraphicAttribute', 
			'LineBreakMeasurer', 'LineMetrics', 'MultipleMaster', 
			'NumericShaper', 'OpenType', 'ShapeGraphicAttribute', 
			'TextAttribute', 'TextHitInfo', 'TextLayout', 
			'TextLayout.CaretPolicy', 'TextMeasurer', 'TransformAttribute'),

		'java/java/java/awt/font',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/font/{FNAME}.html'
	);


	//java.awt.geom 
	$context->addKeywordGroup(array(
			'AffineTransform', 'Arc2D', 'Arc2D.Double', 
			'Arc2D.Float', 'Area', 'CubicCurve2D', 
			'CubicCurve2D.Double', 'CubicCurve2D.Float', 'Dimension2D', 
			'Ellipse2D', 'Ellipse2D.Double', 'Ellipse2D.Float', 
			'FlatteningPathIterator', 'GeneralPath', 'IllegalPathStateException', 
			'Line2D', 'Line2D.Double', 'Line2D.Float', 
			'NoninvertibleTransformException', 'PathIterator', 'Point2D', 
			'Point2D.Double', 'Point2D.Float', 'QuadCurve2D', 
			'QuadCurve2D.Double', 'QuadCurve2D.Float', 'Rectangle2D', 
			'Rectangle2D.Double', 'Rectangle2D.Float', 'RectangularShape', 
			'RoundRectangle2D', 'RoundRectangle2D.Double', 'RoundRectangle2D.Float'),

		'java/java/java/awt/geom',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/geom/{FNAME}.html'
	);


	//java.awt.im 
	$context->addKeywordGroup(array(
			'InputContext', 'InputMethodHighlight', 'InputMethodRequests', 
			'InputSubset'),

		'java/java/java/awt/im',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/im/{FNAME}.html'
	);


	//java.awt.im.spi 
	$context->addKeywordGroup(array(
			'InputMethod', 'InputMethodContext', 'InputMethodDescriptor'),

		'java/java/java/awt/im/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/im/spi/{FNAME}.html'
	);


	//java.awt.image 
	$context->addKeywordGroup(array(
			'AffineTransformOp', 'AreaAveragingScaleFilter', 'BandCombineOp', 
			'BandedSampleModel', 'BufferStrategy', 'BufferedImage', 
			'BufferedImageFilter', 'BufferedImageOp', 'ByteLookupTable', 
			'ColorConvertOp', 'ColorModel', 'ComponentColorModel', 
			'ComponentSampleModel', 'ConvolveOp', 'CropImageFilter', 
			'DataBuffer', 'DataBufferByte', 'DataBufferDouble', 
			'DataBufferFloat', 'DataBufferInt', 'DataBufferShort', 
			'DataBufferUShort', 'DirectColorModel', 'FilteredImageSource', 
			'ImageConsumer', 'ImageFilter', 'ImageObserver', 
			'ImageProducer', 'ImagingOpException', 'IndexColorModel', 
			'Kernel', 'LookupOp', 'LookupTable', 
			'MemoryImageSource', 'MultiPixelPackedSampleModel', 'PackedColorModel', 
			'PixelGrabber', 'PixelInterleavedSampleModel', 'RGBImageFilter', 
			'Raster', 'RasterFormatException', 'RasterOp', 
			'RenderedImage', 'ReplicateScaleFilter', 'RescaleOp', 
			'SampleModel', 'ShortLookupTable', 'SinglePixelPackedSampleModel', 
			'TileObserver', 'VolatileImage', 'WritableRaster', 
			'WritableRenderedImage'),

		'java/java/java/awt/image',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/image/{FNAME}.html'
	);


	//java.awt.image.renderable
	$context->addKeywordGroup(array(
			'ContextualRenderedImageFactory', 'ParameterBlock', 'RenderContext', 
			'RenderableImage', 'RenderableImageOp', 'RenderableImageProducer', 
			'RenderedImageFactory'),

		'java/java/java/awt/image/renderable',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/image/renderable/{FNAME}.html'
	);


	//java.awt.print 
	$context->addKeywordGroup(array(
			'Book', 'PageFormat', 'Pageable', 
			'Paper', 'Printable', 'PrinterAbortException', 
			'PrinterException', 'PrinterGraphics', 'PrinterIOException', 
			'PrinterJob'),

		'java/java/java/awt/print',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/awt/print/{FNAME}.html'
	);


	//java.beans 
	$context->addKeywordGroup(array(
			'AppletInitializer', 'BeanDescriptor', 'BeanInfo', 
			'Beans', 'Customizer', 'DefaultPersistenceDelegate', 
			'DesignMode', 'Encoder', 'EventHandler', 
			'EventSetDescriptor', 'ExceptionListener', 'Expression', 
			'FeatureDescriptor', 'IndexedPropertyChangeEvent', 'IndexedPropertyDescriptor', 
			'IntrospectionException', 'Introspector', 'MethodDescriptor', 
			'ParameterDescriptor', 'PersistenceDelegate', 'PropertyChangeEvent', 
			'PropertyChangeListener', 'PropertyChangeListenerProxy', 'PropertyChangeSupport', 
			'PropertyDescriptor', 'PropertyEditor', 'PropertyEditorManager', 
			'PropertyEditorSupport', 'PropertyVetoException', 'SimpleBeanInfo', 
			'Statement', 'VetoableChangeListener', 'VetoableChangeListenerProxy', 
			'VetoableChangeSupport', 'Visibility', 'XMLDecoder', 
			'XMLEncoder'),

		'java/java/java/beans',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/beans/{FNAME}.html'
	);


	//java.beans.beancontext
	$context->addKeywordGroup(array(
			'BeanContext', 'BeanContextChild', 'BeanContextChildComponentProxy', 
			'BeanContextChildSupport', 'BeanContextContainerProxy', 'BeanContextEvent', 
			'BeanContextMembershipEvent', 'BeanContextMembershipListener', 'BeanContextProxy', 
			'BeanContextServiceAvailableEvent', 'BeanContextServiceProvider', 'BeanContextServiceProviderBeanInfo', 
			'BeanContextServiceRevokedEvent', 'BeanContextServiceRevokedListener', 'BeanContextServices', 
			'BeanContextServicesListener', 'BeanContextServicesSupport', 'BeanContextServicesSupport.BCSSServiceProvider', 
			'BeanContextSupport', 'BeanContextSupport.BCSIterator'),

		'java/java/java/beans/beancontext',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/beans/beancontext/{FNAME}.html'
	);


	//java.io 
	$context->addKeywordGroup(array(
			'BufferedInputStream', 'BufferedOutputStream', 'BufferedReader', 
			'BufferedWriter', 'ByteArrayInputStream', 'ByteArrayOutputStream', 
			'CharArrayReader', 'CharArrayWriter', 'CharConversionException', 
			'Closeable', 'DataInput', 'DataInputStream', 
			'DataOutput', 'DataOutputStream', 'EOFException', 
			'Externalizable', 'File', 'FileDescriptor', 
			'FileFilter', 'FileInputStream', 'FileNotFoundException', 
			'FileOutputStream', 'FilePermission', 'FileReader', 
			'FileWriter', 'FilenameFilter', 'FilterInputStream', 
			'FilterOutputStream', 'FilterReader', 'FilterWriter', 
			'Flushable', 'IOException', 'InputStream', 
			'InputStreamReader', 'InterruptedIOException', 'InvalidClassException', 
			'InvalidObjectException', 'LineNumberInputStream', 'LineNumberReader', 
			'NotActiveException', 'NotSerializableException', 'ObjectInput', 
			'ObjectInputStream', 'ObjectInputStream.GetField', 'ObjectInputValidation', 
			'ObjectOutput', 'ObjectOutputStream', 'ObjectOutputStream.PutField', 
			'ObjectStreamClass', 'ObjectStreamConstants', 'ObjectStreamException', 
			'ObjectStreamField', 'OptionalDataException', 'OutputStream', 
			'OutputStreamWriter', 'PipedInputStream', 'PipedOutputStream', 
			'PipedReader', 'PipedWriter', 'PrintStream', 
			'PrintWriter', 'PushbackInputStream', 'PushbackReader', 
			'RandomAccessFile', 'Reader', 'SequenceInputStream', 
			'Serializable', 'SerializablePermission', 'StreamCorruptedException', 
			'StreamTokenizer', 'StringBufferInputStream', 'StringReader', 
			'StringWriter', 'SyncFailedException', 'UTFDataFormatException', 
			'UnsupportedEncodingException', 'WriteAbortedException', 'Writer'),

		'java/java/java/io',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/io/{FNAME}.html'
	);


	//java.lang 
	$context->addKeywordGroup(array(
			'AbstractMethodError', 'Annotation Types', 'Appendable', 
			'ArithmeticException', 'ArrayIndexOutOfBoundsException', 'ArrayStoreException', 
			'AssertionError', 'Boolean', 'Byte', 
			'CharSequence', 'Character', 'Character.Subset', 
			'Character.UnicodeBlock', 'Class', 'ClassCastException', 
			'ClassCircularityError', 'ClassFormatError', 'ClassLoader', 
			'ClassNotFoundException', 'CloneNotSupportedException', 'Cloneable', 
			'Comparable', 'Compiler', 'Deprecated', 
			'Double', 'Enum', 'EnumConstantNotPresentException', 
			'Error', 'Exception', 'ExceptionInInitializerError', 
			'Float', 'IllegalAccessError', 'IllegalAccessException', 
			'IllegalArgumentException', 'IllegalMonitorStateException', 'IllegalStateException', 
			'IllegalThreadStateException', 'IncompatibleClassChangeError', 'IndexOutOfBoundsException', 
			'InheritableThreadLocal', 'InstantiationError', 'InstantiationException', 
			'Integer', 'InternalError', 'InterruptedException', 
			'Iterable', 'LinkageError', 'Long', 
			'Math', 'NegativeArraySizeException', 'NoClassDefFoundError', 
			'NoSuchFieldError', 'NoSuchFieldException', 'NoSuchMethodError', 
			'NoSuchMethodException', 'NullPointerException', 'Number', 
			'NumberFormatException', 'Object', 'OutOfMemoryError', 
			'Override', 'Package', 'Process', 
			'ProcessBuilder', 'Readable', 'Runnable', 
			'Runtime', 'RuntimeException', 'RuntimePermission', 
			'SecurityException', 'SecurityManager', 'Short', 
			'StackOverflowError', 'StackTraceElement', 'StrictMath', 
			'String', 'StringBuffer', 'StringBuilder', 
			'StringIndexOutOfBoundsException', 'SuppressWarnings', 'System', 
			'Thread', 'Thread.State', 'Thread.UncaughtExceptionHandler', 
			'ThreadDeath', 'ThreadGroup', 'ThreadLocal', 
			'Throwable', 'TypeNotPresentException', 'UnknownError', 
			'UnsatisfiedLinkError', 'UnsupportedClassVersionError', 'UnsupportedOperationException', 
			'VerifyError', 'VirtualMachineError', 'Void'),

		'java/java/java/lang',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/{FNAME}.html'
	);


	//java.lang.annotation
	$context->addKeywordGroup(array(
			'Annotation', 'Annotation Types', 'AnnotationFormatError', 
			'AnnotationTypeMismatchException', 'Documented', 'ElementType', 
			'IncompleteAnnotationException', 'Inherited', 'Retention', 
			'RetentionPolicy', 'Target'),

		'java/java/java/lang/annotation',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/annotation/{FNAME}.html'
	);


	//java.lang.instrument
	$context->addKeywordGroup(array(
			'ClassDefinition', 'ClassFileTransformer', 'IllegalClassFormatException', 
			'Instrumentation', 'UnmodifiableClassException'),

		'java/java/java/lang/instrument',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/instrument/{FNAME}.html'
	);


	//java.lang.management 
	$context->addKeywordGroup(array(
			'ClassLoadingMXBean', 'CompilationMXBean', 'GarbageCollectorMXBean', 
			'ManagementFactory', 'ManagementPermission', 'MemoryMXBean', 
			'MemoryManagerMXBean', 'MemoryNotificationInfo', 'MemoryPoolMXBean', 
			'MemoryType', 'MemoryUsage', 'OperatingSystemMXBean', 
			'RuntimeMXBean', 'ThreadInfo', 'ThreadMXBean'),

		'java/java/java/lang/management',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/management/{FNAME}.html'
	);


	//java.lang.ref 
	$context->addKeywordGroup(array(
			'PhantomReference', 'Reference', 'ReferenceQueue', 
			'SoftReference', 'WeakReference'),

		'java/java/java/lang/ref',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/ref/{FNAME}.html'
	);


	//java.lang.reflect 
	$context->addKeywordGroup(array(
			'AccessibleObject', 'AnnotatedElement', 'Array', 
			'Constructor', 'Field', 'GenericArrayType', 
			'GenericDeclaration', 'GenericSignatureFormatError', 'InvocationHandler', 
			'InvocationTargetException', 'MalformedParameterizedTypeException', 'Member', 
			'Method', 'Modifier', 'ParameterizedType', 
			'Proxy', 'ReflectPermission', 'Type', 
			'TypeVariable', 'UndeclaredThrowableException', 'WildcardType'),

		'java/java/java/lang/reflect',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/lang/reflect/{FNAME}.html'
	);


	//java.math
	$context->addKeywordGroup(array(
			'BigDecimal', 'BigInteger', 'MathContext', 
			'RoundingMode'),

		'java/java/java/math',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/math/{FNAME}.html'
	);


	//java.net 
	$context->addKeywordGroup(array(
			'Authenticator', 'Authenticator.RequestorType', 'BindException', 
			'CacheRequest', 'CacheResponse', 'ConnectException', 
			'ContentHandler', 'ContentHandlerFactory', 'CookieHandler', 
			'DatagramPacket', 'DatagramSocket', 'DatagramSocketImpl', 
			'DatagramSocketImplFactory', 'FileNameMap', 'HttpRetryException', 
			'HttpURLConnection', 'Inet4Address', 'Inet6Address', 
			'InetAddress', 'InetSocketAddress', 'JarURLConnection', 
			'MalformedURLException', 'MulticastSocket', 'NetPermission', 
			'NetworkInterface', 'NoRouteToHostException', 'PasswordAuthentication', 
			'PortUnreachableException', 'ProtocolException', 'Proxy', 
			'Proxy.Type', 'ProxySelector', 'ResponseCache', 
			'SecureCacheResponse', 'ServerSocket', 'Socket', 
			'SocketAddress', 'SocketException', 'SocketImpl', 
			'SocketImplFactory', 'SocketOptions', 'SocketPermission', 
			'SocketTimeoutException', 'URI', 'URISyntaxException', 
			'URL', 'URLClassLoader', 'URLConnection', 
			'URLDecoder', 'URLEncoder', 'URLStreamHandler', 
			'URLStreamHandlerFactory', 'UnknownHostException', 'UnknownServiceException'),

		'java/java/java/net',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/net/{FNAME}.html'
	);


	//java.nio 
	$context->addKeywordGroup(array(
			'Buffer', 'BufferOverflowException', 'BufferUnderflowException', 
			'ByteBuffer', 'ByteOrder', 'CharBuffer', 
			'DoubleBuffer', 'FloatBuffer', 'IntBuffer', 
			'InvalidMarkException', 'LongBuffer', 'MappedByteBuffer', 
			'ReadOnlyBufferException', 'ShortBuffer'),

		'java/java/java/nio',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/nio/{FNAME}.html'
	);


	//java.nio.channels 
	$context->addKeywordGroup(array(
			'AlreadyConnectedException', 'AsynchronousCloseException', 'ByteChannel', 
			'CancelledKeyException', 'Channel', 'Channels', 
			'ClosedByInterruptException', 'ClosedChannelException', 'ClosedSelectorException', 
			'ConnectionPendingException', 'DatagramChannel', 'FileChannel', 
			'FileChannel.MapMode', 'FileLock', 'FileLockInterruptionException', 
			'GatheringByteChannel', 'IllegalBlockingModeException', 'IllegalSelectorException', 
			'InterruptibleChannel', 'NoConnectionPendingException', 'NonReadableChannelException', 
			'NonWritableChannelException', 'NotYetBoundException', 'NotYetConnectedException', 
			'OverlappingFileLockException', 'Pipe', 'Pipe.SinkChannel', 
			'Pipe.SourceChannel', 'ReadableByteChannel', 'ScatteringByteChannel', 
			'SelectableChannel', 'SelectionKey', 'Selector', 
			'ServerSocketChannel', 'SocketChannel', 'UnresolvedAddressException', 
			'UnsupportedAddressTypeException', 'WritableByteChannel'),

		'java/java/java/nio/channels',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/nio/channels/{FNAME}.html'
	);


	//java.nio.channels.spi 
	$context->addKeywordGroup(array(
			'AbstractInterruptibleChannel', 'AbstractSelectableChannel', 'AbstractSelectionKey', 
			'AbstractSelector', 'SelectorProvider'),

		'java/java/java/nio/channels/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/nio/channels/spi/{FNAME}.html'
	);


	//java.nio.charset
	$context->addKeywordGroup(array(
			'CharacterCodingException', 'Charset', 'CharsetDecoder', 
			'CharsetEncoder', 'CoderMalfunctionError', 'CoderResult', 
			'CodingErrorAction', 'IllegalCharsetNameException', 'MalformedInputException', 
			'UnmappableCharacterException', 'UnsupportedCharsetException'),

		'java/java/java/nio/charset',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/nio/charset/{FNAME}.html'
	);


	//java.nio.charset.spi 
	$context->addKeywordGroup(array(
			'CharsetProvider'),

		'java/java/java/nio/charset/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/nio/charset/spi/{FNAME}.html'
	);


	//java.rmi 
	$context->addKeywordGroup(array(
			'AccessException', 'AlreadyBoundException', 'ConnectException', 
			'ConnectIOException', 'MarshalException', 'MarshalledObject', 
			'Naming', 'NoSuchObjectException', 'NotBoundException', 
			'RMISecurityException', 'RMISecurityManager', 'Remote', 
			'RemoteException', 'ServerError', 'ServerException', 
			'ServerRuntimeException', 'StubNotFoundException', 'UnexpectedException', 
			'UnknownHostException', 'UnmarshalException'),

		'java/java/java/rmi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/rmi/{FNAME}.html'
	);


	//java.rmi.activation 
	$context->addKeywordGroup(array(
			'Activatable', 'ActivateFailedException', 'ActivationDesc', 
			'ActivationException', 'ActivationGroup', 'ActivationGroupDesc', 
			'ActivationGroupDesc.CommandEnvironment', 'ActivationGroupID', 'ActivationGroup_Stub', 
			'ActivationID', 'ActivationInstantiator', 'ActivationMonitor', 
			'ActivationSystem', 'Activator', 'UnknownGroupException', 
			'UnknownObjectException'),

		'java/java/java/rmi/activation',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/rmi/activation/{FNAME}.html'
	);


	//java.rmi.dgc 
	$context->addKeywordGroup(array(
			'DGC', 'Lease', 'VMID'),

		'java/java/java/rmi/dgc',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/rmi/dgc/{FNAME}.html'
	);


	//java.rmi.registry 
	$context->addKeywordGroup(array(
			'LocateRegistry', 'Registry', 'RegistryHandler'),

		'java/java/java/rmi/registry',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/rmi/registry/{FNAME}.html'
	);


	//java.rmi.server 
	$context->addKeywordGroup(array(
			'ExportException', 'LoaderHandler', 'LogStream', 
			'ObjID', 'Operation', 'RMIClassLoader', 
			'RMIClassLoaderSpi', 'RMIClientSocketFactory', 'RMIFailureHandler', 
			'RMIServerSocketFactory', 'RMISocketFactory', 'RemoteCall', 
			'RemoteObject', 'RemoteObjectInvocationHandler', 'RemoteRef', 
			'RemoteServer', 'RemoteStub', 'ServerCloneException', 
			'ServerNotActiveException', 'ServerRef', 'Skeleton', 
			'SkeletonMismatchException', 'SkeletonNotFoundException', 'SocketSecurityException', 
			'UID', 'UnicastRemoteObject', 'Unreferenced'),

		'java/java/java/rmi/server',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/rmi/server/{FNAME}.html'
	);


	//java.security
	$context->addKeywordGroup(array(
			'AccessControlContext', 'AccessControlException', 'AccessController', 
			'AlgorithmParameterGenerator', 'AlgorithmParameterGeneratorSpi', 'AlgorithmParameters', 
			'AlgorithmParametersSpi', 'AllPermission', 'AuthProvider', 
			'BasicPermission', 'Certificate', 'CodeSigner', 
			'CodeSource', 'DigestException', 'DigestInputStream', 
			'DigestOutputStream', 'DomainCombiner', 'GeneralSecurityException', 
			'Guard', 'GuardedObject', 'Identity', 
			'IdentityScope', 'InvalidAlgorithmParameterException', 'InvalidKeyException', 
			'InvalidParameterException', 'Key', 'KeyException', 
			'KeyFactory', 'KeyFactorySpi', 'KeyManagementException', 
			'KeyPair', 'KeyPairGenerator', 'KeyPairGeneratorSpi', 
			'KeyRep', 'KeyRep.Type', 'KeyStore', 
			'KeyStore.Builder', 'KeyStore.CallbackHandlerProtection', 'KeyStore.Entry', 
			'KeyStore.LoadStoreParameter', 'KeyStore.PasswordProtection', 'KeyStore.PrivateKeyEntry', 
			'KeyStore.ProtectionParameter', 'KeyStore.SecretKeyEntry', 'KeyStore.TrustedCertificateEntry', 
			'KeyStoreException', 'KeyStoreSpi', 'MessageDigest', 
			'MessageDigestSpi', 'NoSuchAlgorithmException', 'NoSuchProviderException', 
			'Permission', 'PermissionCollection', 'Permissions', 
			'Policy', 'Principal', 'PrivateKey', 
			'PrivilegedAction', 'PrivilegedActionException', 'PrivilegedExceptionAction', 
			'ProtectionDomain', 'Provider', 'Provider.Service', 
			'ProviderException', 'PublicKey', 'SecureClassLoader', 
			'SecureRandom', 'SecureRandomSpi', 'Security', 
			'SecurityPermission', 'Signature', 'SignatureException', 
			'SignatureSpi', 'SignedObject', 'Signer', 
			'Timestamp', 'UnrecoverableEntryException', 'UnrecoverableKeyException', 
			'UnresolvedPermission'),

		'java/java/java/security',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/security/{FNAME}.html'
	);


	//java.security.acl 
	$context->addKeywordGroup(array(
			'Acl', 'AclEntry', 'AclNotFoundException', 
			'Group', 'LastOwnerException', 'NotOwnerException', 
			'Owner', 'Permission'),

		'java/java/java/security/acl',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/security/acl/{FNAME}.html'
	);


	//java.security.cert 
	$context->addKeywordGroup(array(
			'CRL', 'CRLException', 'CRLSelector', 
			'CertPath', 'CertPath.CertPathRep', 'CertPathBuilder', 
			'CertPathBuilderException', 'CertPathBuilderResult', 'CertPathBuilderSpi', 
			'CertPathParameters', 'CertPathValidator', 'CertPathValidatorException', 
			'CertPathValidatorResult', 'CertPathValidatorSpi', 'CertSelector', 
			'CertStore', 'CertStoreException', 'CertStoreParameters', 
			'CertStoreSpi', 'Certificate', 'Certificate.CertificateRep', 
			'CertificateEncodingException', 'CertificateException', 'CertificateExpiredException', 
			'CertificateFactory', 'CertificateFactorySpi', 'CertificateNotYetValidException', 
			'CertificateParsingException', 'CollectionCertStoreParameters', 'LDAPCertStoreParameters', 
			'PKIXBuilderParameters', 'PKIXCertPathBuilderResult', 'PKIXCertPathChecker', 
			'PKIXCertPathValidatorResult', 'PKIXParameters', 'PolicyNode', 
			'PolicyQualifierInfo', 'TrustAnchor', 'X509CRL', 
			'X509CRLEntry', 'X509CRLSelector', 'X509CertSelector', 
			'X509Certificate', 'X509Extension'),

		'java/java/java/security/cert',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/security/cert/{FNAME}.html'
	);


	//java.security.interfaces
	$context->addKeywordGroup(array(
			'DSAKey', 'DSAKeyPairGenerator', 'DSAParams', 
			'DSAPrivateKey', 'DSAPublicKey', 'ECKey', 
			'ECPrivateKey', 'ECPublicKey', 'RSAKey', 
			'RSAMultiPrimePrivateCrtKey', 'RSAPrivateCrtKey', 'RSAPrivateKey', 
			'RSAPublicKey'),

		'java/java/java/security/interfaces',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/security/interfaces/{FNAME}.html'
	);


	//java.security.spec 
	$context->addKeywordGroup(array(
			'AlgorithmParameterSpec', 'DSAParameterSpec', 'DSAPrivateKeySpec', 
			'DSAPublicKeySpec', 'ECField', 'ECFieldF2m', 
			'ECFieldFp', 'ECGenParameterSpec', 'ECParameterSpec', 
			'ECPoint', 'ECPrivateKeySpec', 'ECPublicKeySpec', 
			'EllipticCurve', 'EncodedKeySpec', 'InvalidKeySpecException', 
			'InvalidParameterSpecException', 'KeySpec', 'MGF1ParameterSpec', 
			'PKCS8EncodedKeySpec', 'PSSParameterSpec', 'RSAKeyGenParameterSpec', 
			'RSAMultiPrimePrivateCrtKeySpec', 'RSAOtherPrimeInfo', 'RSAPrivateCrtKeySpec', 
			'RSAPrivateKeySpec', 'RSAPublicKeySpec', 'X509EncodedKeySpec'),

		'java/java/java/security/spec',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/security/spec/{FNAME}.html'
	);


	//java.sql 
	$context->addKeywordGroup(array(
			'Array', 'BatchUpdateException', 'Blob', 
			'CallableStatement', 'Clob', 'Connection', 
			'DataTruncation', 'DatabaseMetaData', 'Date', 
			'Driver', 'DriverManager', 'DriverPropertyInfo', 
			'ParameterMetaData', 'PreparedStatement', 'Ref', 
			'ResultSet', 'ResultSetMetaData', 'SQLData', 
			'SQLException', 'SQLInput', 'SQLOutput', 
			'SQLPermission', 'SQLWarning', 'Savepoint', 
			'Statement', 'Struct', 'Time', 
			'Timestamp', 'Types'),

		'java/java/java/sql',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/sql/{FNAME}.html'
	);


	//java.text 
	$context->addKeywordGroup(array(
			'Annotation', 'AttributedCharacterIterator', 'AttributedCharacterIterator.Attribute', 
			'AttributedString', 'Bidi', 'BreakIterator', 
			'CharacterIterator', 'ChoiceFormat', 'CollationElementIterator', 
			'CollationKey', 'Collator', 'DateFormat', 
			'DateFormat.Field', 'DateFormatSymbols', 'DecimalFormat', 
			'DecimalFormatSymbols', 'FieldPosition', 'Format', 
			'Format.Field', 'MessageFormat', 'MessageFormat.Field', 
			'NumberFormat', 'NumberFormat.Field', 'ParseException', 
			'ParsePosition', 'RuleBasedCollator', 'SimpleDateFormat', 
			'StringCharacterIterator'),

		'java/java/java/text',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/text/{FNAME}.html'
	);


	//java.util 
	$context->addKeywordGroup(array(
			'AbstractCollection', 'AbstractList', 'AbstractMap', 
			'AbstractQueue', 'AbstractSequentialList', 'AbstractSet', 
			'ArrayList', 'Arrays', 'BitSet', 
			'Calendar', 'Collection', 'Collections', 
			'Comparator', 'ConcurrentModificationException', 'Currency', 
			'Date', 'Dictionary', 'DuplicateFormatFlagsException', 
			'EmptyStackException', 'EnumMap', 'EnumSet', 
			'Enumeration', 'EventListener', 'EventListenerProxy', 
			'EventObject', 'FormatFlagsConversionMismatchException', 'Formattable', 
			'FormattableFlags', 'Formatter', 'Formatter.BigDecimalLayoutForm', 
			'FormatterClosedException', 'GregorianCalendar', 'HashMap', 
			'HashSet', 'Hashtable', 'IdentityHashMap', 
			'IllegalFormatCodePointException', 'IllegalFormatConversionException', 'IllegalFormatException', 
			'IllegalFormatFlagsException', 'IllegalFormatPrecisionException', 'IllegalFormatWidthException', 
			'InputMismatchException', 'InvalidPropertiesFormatException', 'Iterator', 
			'LinkedHashMap', 'LinkedHashSet', 'LinkedList', 
			'List', 'ListIterator', 'ListResourceBundle', 
			'Locale', 'Map', 'Map.Entry', 
			'MissingFormatArgumentException', 'MissingFormatWidthException', 'MissingResourceException', 
			'NoSuchElementException', 'Observable', 'Observer', 
			'PriorityQueue', 'Properties', 'PropertyPermission', 
			'PropertyResourceBundle', 'Queue', 'Random', 
			'RandomAccess', 'ResourceBundle', 'Scanner', 
			'Set', 'SimpleTimeZone', 'SortedMap', 
			'SortedSet', 'Stack', 'StringTokenizer', 
			'TimeZone', 'Timer', 'TimerTask', 
			'TooManyListenersException', 'TreeMap', 'TreeSet', 
			'UUID', 'UnknownFormatConversionException', 'UnknownFormatFlagsException', 
			'Vector', 'WeakHashMap'),

		'java/java/java/util',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/{FNAME}.html'
	);


	//java.util.concurrent 
	$context->addKeywordGroup(array(
			'AbstractExecutorService', 'ArrayBlockingQueue', 'BlockingQueue', 
			'BrokenBarrierException', 'Callable', 'CancellationException', 
			'CompletionService', 'ConcurrentHashMap', 'ConcurrentLinkedQueue', 
			'ConcurrentMap', 'CopyOnWriteArrayList', 'CopyOnWriteArraySet', 
			'CountDownLatch', 'CyclicBarrier', 'DelayQueue', 
			'Delayed', 'Exchanger', 'ExecutionException', 
			'Executor', 'ExecutorCompletionService', 'ExecutorService', 
			'Executors', 'Future', 'FutureTask', 
			'LinkedBlockingQueue', 'PriorityBlockingQueue', 'RejectedExecutionException', 
			'RejectedExecutionHandler', 'ScheduledExecutorService', 'ScheduledFuture', 
			'ScheduledThreadPoolExecutor', 'Semaphore', 'SynchronousQueue', 
			'ThreadFactory', 'ThreadPoolExecutor', 'ThreadPoolExecutor.AbortPolicy', 
			'ThreadPoolExecutor.CallerRunsPolicy', 'ThreadPoolExecutor.DiscardOldestPolicy', 'ThreadPoolExecutor.DiscardPolicy', 
			'TimeUnit', 'TimeoutException'),

		'java/java/java/util/concurrent',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/concurrent/{FNAME}.html'
	);


	//java.util.concurrent.atomic 
	$context->addKeywordGroup(array(
			'AtomicBoolean', 'AtomicInteger', 'AtomicIntegerArray', 
			'AtomicIntegerFieldUpdater', 'AtomicLong', 'AtomicLongArray', 
			'AtomicLongFieldUpdater', 'AtomicMarkableReference', 'AtomicReference', 
			'AtomicReferenceArray', 'AtomicReferenceFieldUpdater', 'AtomicStampedReference'),

		'java/java/java/util/concurrent/atomic',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/concurrent/atomic/{FNAME}.html'
	);


	//java.util.concurrent.locks 
	$context->addKeywordGroup(array(
			'AbstractQueuedSynchronizer', 'Condition', 'Lock', 
			'LockSupport', 'ReadWriteLock', 'ReentrantLock', 
			'ReentrantReadWriteLock', 'ReentrantReadWriteLock.ReadLock', 'ReentrantReadWriteLock.WriteLock'),

		'java/java/java/util/concurrent/locks',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/concurrent/locks/{FNAME}.html'
	);


	//java.util.jar 
	$context->addKeywordGroup(array(
			'Attributes', 'Attributes.Name', 'JarEntry', 
			'JarException', 'JarFile', 'JarInputStream', 
			'JarOutputStream', 'Manifest', 'Pack200', 
			'Pack200.Packer', 'Pack200.Unpacker'),

		'java/java/java/util/jar',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/jar/{FNAME}.html'
	);


	//java.util.logging 
	$context->addKeywordGroup(array(
			'ConsoleHandler', 'ErrorManager', 'FileHandler', 
			'Filter', 'Formatter', 'Handler', 
			'Level', 'LogManager', 'LogRecord', 
			'Logger', 'LoggingMXBean', 'LoggingPermission', 
			'MemoryHandler', 'SimpleFormatter', 'SocketHandler', 
			'StreamHandler', 'XMLFormatter'),

		'java/java/java/util/logging',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/logging/{FNAME}.html'
	);


	//java.util.prefs 
	$context->addKeywordGroup(array(
			'AbstractPreferences', 'BackingStoreException', 'InvalidPreferencesFormatException', 
			'NodeChangeEvent', 'NodeChangeListener', 'PreferenceChangeEvent', 
			'PreferenceChangeListener', 'Preferences', 'PreferencesFactory'),

		'java/java/java/util/prefs',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/prefs/{FNAME}.html'
	);


	//java.util.regex 
	$context->addKeywordGroup(array(
			'MatchResult', 'Matcher', 'Pattern', 
			'PatternSyntaxException'),

		'java/java/java/util/regex',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/regex/{FNAME}.html'
	);


	//java.util.zip 
	$context->addKeywordGroup(array(
			'Adler32', 'CRC32', 'CheckedInputStream', 
			'CheckedOutputStream', 'Checksum', 'DataFormatException', 
			'Deflater', 'DeflaterOutputStream', 'GZIPInputStream', 
			'GZIPOutputStream', 'Inflater', 'InflaterInputStream', 
			'ZipEntry', 'ZipException', 'ZipFile', 
			'ZipInputStream', 'ZipOutputStream'),

		'java/java/java/util/zip',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/java/util/zip/{FNAME}.html'
	);


	//javax.accessibility 
	$context->addKeywordGroup(array(
			'Accessible', 'AccessibleAction', 'AccessibleAttributeSequence', 
			'AccessibleBundle', 'AccessibleComponent', 'AccessibleContext', 
			'AccessibleEditableText', 'AccessibleExtendedComponent', 'AccessibleExtendedTable', 
			'AccessibleExtendedText', 'AccessibleHyperlink', 'AccessibleHypertext', 
			'AccessibleIcon', 'AccessibleKeyBinding', 'AccessibleRelation', 
			'AccessibleRelationSet', 'AccessibleResourceBundle', 'AccessibleRole', 
			'AccessibleSelection', 'AccessibleState', 'AccessibleStateSet', 
			'AccessibleStreamable', 'AccessibleTable', 'AccessibleTableModelChange', 
			'AccessibleText', 'AccessibleTextSequence', 'AccessibleValue'),

		'java/java/javax/accessibility',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/accessibility/{FNAME}.html'
	);


	//javax.activity 
	$context->addKeywordGroup(array(
			'ActivityCompletedException', 'ActivityRequiredException', 'InvalidActivityException'),

		'java/java/javax/activity',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/activity/{FNAME}.html'
	);


	//javax.crypto 
	$context->addKeywordGroup(array(
			'BadPaddingException', 'Cipher', 'CipherInputStream', 
			'CipherOutputStream', 'CipherSpi', 'EncryptedPrivateKeyInfo', 
			'ExemptionMechanism', 'ExemptionMechanismException', 'ExemptionMechanismSpi', 
			'IllegalBlockSizeException', 'KeyAgreement', 'KeyAgreementSpi', 
			'KeyGenerator', 'KeyGeneratorSpi', 'Mac', 
			'MacSpi', 'NoSuchPaddingException', 'NullCipher', 
			'SealedObject', 'SecretKey', 'SecretKeyFactory', 
			'SecretKeyFactorySpi', 'ShortBufferException'),

		'java/java/javax/crypto',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/crypto/{FNAME}.html'
	);


	//javax.crypto.interfaces
	$context->addKeywordGroup(array(
			'DHKey', 'DHPrivateKey', 'DHPublicKey', 
			'PBEKey'),

		'java/java/javax/crypto/interfaces',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/crypto/interfaces/{FNAME}.html'
	);


	//javax.crypto.spec 
	$context->addKeywordGroup(array(
			'DESKeySpec', 'DESedeKeySpec', 'DHGenParameterSpec', 
			'DHParameterSpec', 'DHPrivateKeySpec', 'DHPublicKeySpec', 
			'IvParameterSpec', 'OAEPParameterSpec', 'PBEKeySpec', 
			'PBEParameterSpec', 'PSource', 'PSource.PSpecified', 
			'RC2ParameterSpec', 'RC5ParameterSpec', 'SecretKeySpec'),

		'java/java/javax/crypto/spec',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/crypto/spec/{FNAME}.html'
	);


	//javax.imageio 
	$context->addKeywordGroup(array(
			'IIOException', 'IIOImage', 'IIOParam', 
			'IIOParamController', 'ImageIO', 'ImageReadParam', 
			'ImageReader', 'ImageTranscoder', 'ImageTypeSpecifier', 
			'ImageWriteParam', 'ImageWriter'),

		'java/java/javax/imageio',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/{FNAME}.html'
	);


	//javax.imageio.event 
	$context->addKeywordGroup(array(
			'IIOReadProgressListener', 'IIOReadUpdateListener', 'IIOReadWarningListener', 
			'IIOWriteProgressListener', 'IIOWriteWarningListener'),

		'java/java/javax/imageio/event',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/event/{FNAME}.html'
	);


	//javax.imageio.metadata   
	$context->addKeywordGroup(array(
			'IIOInvalidTreeException', 'IIOMetadata', 'IIOMetadataController', 
			'IIOMetadataFormat', 'IIOMetadataFormatImpl', 'IIOMetadataNode'),

		'java/java/javax/imageio/metadata',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/metadata/{FNAME}.html'
	);


	//javax.imageio.plugins.bmp   
	$context->addKeywordGroup(array(
			'BMPImageWriteParam'),

		'java/java/javax/imageio/plugins/bmp',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/plugins/bmp/{FNAME}.html'
	);


	//javax.imageio.plugins.jpeg   
	$context->addKeywordGroup(array(
			'JPEGHuffmanTable', 'JPEGImageReadParam', 'JPEGImageWriteParam', 
			'JPEGQTable'),

		'java/java/javax/imageio/plugins/jpeg',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/plugins/jpeg/{FNAME}.html'
	);


	//javax.imageio.spi 
	$context->addKeywordGroup(array(
			'IIORegistry', 'IIOServiceProvider', 'ImageInputStreamSpi', 
			'ImageOutputStreamSpi', 'ImageReaderSpi', 'ImageReaderWriterSpi', 
			'ImageTranscoderSpi', 'ImageWriterSpi', 'RegisterableService', 
			'ServiceRegistry', 'ServiceRegistry.Filter'),

		'java/java/javax/imageio/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/spi/{FNAME}.html'
	);


	//javax.imageio.stream   
	$context->addKeywordGroup(array(
			'FileCacheImageInputStream', 'FileCacheImageOutputStream', 'FileImageInputStream', 
			'FileImageOutputStream', 'IIOByteBuffer', 'ImageInputStream', 
			'ImageInputStreamImpl', 'ImageOutputStream', 'ImageOutputStreamImpl', 
			'MemoryCacheImageInputStream', 'MemoryCacheImageOutputStream'),

		'java/java/javax/imageio/stream',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/imageio/stream/{FNAME}.html'
	);


	//javax.management  
	$context->addKeywordGroup(array(
			'Attribute', 'AttributeChangeNotification', 'AttributeChangeNotificationFilter', 
			'AttributeList', 'AttributeNotFoundException', 'AttributeValueExp', 
			'BadAttributeValueExpException', 'BadBinaryOpValueExpException', 'BadStringOperationException', 
			'DefaultLoaderRepository', 'Descriptor', 'DescriptorAccess', 
			'DynamicMBean', 'InstanceAlreadyExistsException', 'InstanceNotFoundException', 
			'IntrospectionException', 'InvalidApplicationException', 'InvalidAttributeValueException', 
			'JMException', 'JMRuntimeException', 'ListenerNotFoundException', 
			'MBeanAttributeInfo', 'MBeanConstructorInfo', 'MBeanException', 
			'MBeanFeatureInfo', 'MBeanInfo', 'MBeanNotificationInfo', 
			'MBeanOperationInfo', 'MBeanParameterInfo', 'MBeanPermission', 
			'MBeanRegistration', 'MBeanRegistrationException', 'MBeanServer', 
			'MBeanServerBuilder', 'MBeanServerConnection', 'MBeanServerDelegate', 
			'MBeanServerDelegateMBean', 'MBeanServerFactory', 'MBeanServerInvocationHandler', 
			'MBeanServerNotification', 'MBeanServerPermission', 'MBeanTrustPermission', 
			'MalformedObjectNameException', 'NotCompliantMBeanException', 'Notification', 
			'NotificationBroadcaster', 'NotificationBroadcasterSupport', 'NotificationEmitter', 
			'NotificationFilter', 'NotificationFilterSupport', 'NotificationListener', 
			'ObjectInstance', 'ObjectName', 'OperationsException', 
			'PersistentMBean', 'Query', 'QueryEval', 
			'QueryExp', 'ReflectionException', 'RuntimeErrorException', 
			'RuntimeMBeanException', 'RuntimeOperationsException', 'ServiceNotFoundException', 
			'StandardMBean', 'StringValueExp', 'ValueExp'),

		'java/java/javax/management',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/{FNAME}.html'
	);


	//javax.management.loading   
	$context->addKeywordGroup(array(
			'ClassLoaderRepository', 'DefaultLoaderRepository', 'MLet', 
			'MLetMBean', 'PrivateClassLoader', 'PrivateMLet'),

		'java/java/javax/management/loading',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/loading/{FNAME}.html'
	);


	//javax.management.modelmbean   
	$context->addKeywordGroup(array(
			'DescriptorSupport', 'InvalidTargetObjectTypeException', 'ModelMBean', 
			'ModelMBeanAttributeInfo', 'ModelMBeanConstructorInfo', 'ModelMBeanInfo', 
			'ModelMBeanInfoSupport', 'ModelMBeanNotificationBroadcaster', 'ModelMBeanNotificationInfo', 
			'ModelMBeanOperationInfo', 'RequiredModelMBean', 'XMLParseException'),

		'java/java/javax/management/modelmbean',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/modelmbean/{FNAME}.html'
	);


	//javax.management.monitor   
	$context->addKeywordGroup(array(
			'CounterMonitor', 'CounterMonitorMBean', 'GaugeMonitor', 
			'GaugeMonitorMBean', 'Monitor', 'MonitorMBean', 
			'MonitorNotification', 'MonitorSettingException', 'StringMonitor', 
			'StringMonitorMBean'),

		'java/java/javax/management/monitor',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/monitor/{FNAME}.html'
	);


	//javax.management.openmbean   
	$context->addKeywordGroup(array(
			'ArrayType', 'CompositeData', 'CompositeDataSupport', 
			'CompositeType', 'InvalidKeyException', 'InvalidOpenTypeException', 
			'KeyAlreadyExistsException', 'OpenDataException', 'OpenMBeanAttributeInfo', 
			'OpenMBeanAttributeInfoSupport', 'OpenMBeanConstructorInfo', 'OpenMBeanConstructorInfoSupport', 
			'OpenMBeanInfo', 'OpenMBeanInfoSupport', 'OpenMBeanOperationInfo', 
			'OpenMBeanOperationInfoSupport', 'OpenMBeanParameterInfo', 'OpenMBeanParameterInfoSupport', 
			'OpenType', 'SimpleType', 'TabularData', 
			'TabularDataSupport', 'TabularType'),

		'java/java/javax/management/openmbean',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/openmbean/{FNAME}.html'
	);


	//javax.management.relation   
	$context->addKeywordGroup(array(
			'Relation', 'RelationServiceMBean', 'RelationSupportMBean', 
			'RelationType'),

		'java/java/javax/management/relation',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/relation/{FNAME}.html'
	);


	//MBeanServerNotificationFilter
	$context->addKeywordGroup(array(
			'InvalidRelationIdException', 'InvalidRelationServiceException', 'InvalidRelationTypeException', 
			'InvalidRoleInfoException', 'InvalidRoleValueException', 'RelationException', 
			'RelationNotFoundException', 'RelationNotification', 'RelationService', 
			'RelationServiceNotRegisteredException', 'RelationSupport', 'RelationTypeNotFoundException', 
			'RelationTypeSupport', 'Role', 'RoleInfo', 
			'RoleInfoNotFoundException', 'RoleList', 'RoleNotFoundException', 
			'RoleResult', 'RoleStatus', 'RoleUnresolved', 
			'RoleUnresolvedList'),

		'java/java/MBeanServerNotificationFilter',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/MBeanServerNotificationFilter/{FNAME}.html'
	);


	//javax.management.remote   
	$context->addKeywordGroup(array(
			'JMXAuthenticator', 'JMXConnectionNotification', 'JMXConnector', 
			'JMXConnectorFactory', 'JMXConnectorProvider', 'JMXConnectorServer', 
			'JMXConnectorServerFactory', 'JMXConnectorServerMBean', 'JMXConnectorServerProvider', 
			'JMXPrincipal', 'JMXProviderException', 'JMXServerErrorException', 
			'JMXServiceURL', 'MBeanServerForwarder', 'NotificationResult', 
			'SubjectDelegationPermission', 'TargetedNotification'),

		'java/java/javax/management/remote',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/remote/{FNAME}.html'
	);


	//javax.management.remote.rmi   
	$context->addKeywordGroup(array(
			'RMIConnection', 'RMIConnectionImpl', 'RMIConnectionImpl_Stub', 
			'RMIConnector', 'RMIConnectorServer', 'RMIIIOPServerImpl', 
			'RMIJRMPServerImpl', 'RMIServer', 'RMIServerImpl', 
			'RMIServerImpl_Stub'),

		'java/java/javax/management/remote/rmi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/remote/rmi/{FNAME}.html'
	);


	//javax.management.timer  
	$context->addKeywordGroup(array(
			'Timer', 'TimerAlarmClockNotification', 'TimerMBean', 
			'TimerNotification'),

		'java/java/javax/management/timer',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/management/timer/{FNAME}.html'
	);


	//javax.naming  
	$context->addKeywordGroup(array(
			'AuthenticationException', 'AuthenticationNotSupportedException', 'BinaryRefAddr', 
			'Binding', 'CannotProceedException', 'CommunicationException', 
			'CompositeName', 'CompoundName', 'ConfigurationException', 
			'Context', 'ContextNotEmptyException', 'InitialContext', 
			'InsufficientResourcesException', 'InterruptedNamingException', 'InvalidNameException', 
			'LimitExceededException', 'LinkException', 'LinkLoopException', 
			'LinkRef', 'MalformedLinkException', 'Name', 
			'NameAlreadyBoundException', 'NameClassPair', 'NameNotFoundException', 
			'NameParser', 'NamingEnumeration', 'NamingException', 
			'NamingSecurityException', 'NoInitialContextException', 'NoPermissionException', 
			'NotContextException', 'OperationNotSupportedException', 'PartialResultException', 
			'RefAddr', 'Reference', 'Referenceable', 
			'ReferralException', 'ServiceUnavailableException', 'SizeLimitExceededException', 
			'StringRefAddr', 'TimeLimitExceededException'),

		'java/java/javax/naming',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/naming/{FNAME}.html'
	);


	//javax.naming.directory   
	$context->addKeywordGroup(array(
			'Attribute', 'AttributeInUseException', 'AttributeModificationException', 
			'Attributes', 'BasicAttribute', 'BasicAttributes', 
			'DirContext', 'InitialDirContext', 'InvalidAttributeIdentifierException', 
			'InvalidAttributeValueException', 'InvalidAttributesException', 'InvalidSearchControlsException', 
			'InvalidSearchFilterException', 'ModificationItem', 'NoSuchAttributeException', 
			'SchemaViolationException', 'SearchControls', 'SearchResult'),

		'java/java/javax/naming/directory',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/naming/directory/{FNAME}.html'
	);


	//javax.naming.event 
	$context->addKeywordGroup(array(
			'EventContext', 'EventDirContext', 'NamespaceChangeListener', 
			'NamingEvent', 'NamingExceptionEvent', 'NamingListener', 
			'ObjectChangeListener'),

		'java/java/javax/naming/event',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/naming/event/{FNAME}.html'
	);


	//javax.naming.ldap  
	$context->addKeywordGroup(array(
			'BasicControl', 'Control', 'ControlFactory', 
			'ExtendedRequest', 'ExtendedResponse', 'HasControls', 
			'InitialLdapContext', 'LdapContext', 'LdapName', 
			'LdapReferralException', 'ManageReferralControl', 'PagedResultsControl', 
			'PagedResultsResponseControl', 'Rdn', 'SortControl', 
			'SortKey', 'SortResponseControl', 'StartTlsRequest', 
			'StartTlsResponse', 'UnsolicitedNotification', 'UnsolicitedNotificationEvent', 
			'UnsolicitedNotificationListener'),

		'java/java/javax/naming/ldap',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/naming/ldap/{FNAME}.html'
	);


	//javax.naming.spi  
	$context->addKeywordGroup(array(
			'DirObjectFactory', 'DirStateFactory', 'DirStateFactory.Result', 
			'DirectoryManager', 'InitialContextFactory', 'InitialContextFactoryBuilder', 
			'NamingManager', 'ObjectFactory', 'ObjectFactoryBuilder', 
			'ResolveResult', 'Resolver', 'StateFactory'),

		'java/java/javax/naming/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/naming/spi/{FNAME}.html'
	);


	//javax.net   
	$context->addKeywordGroup(array(
			'ServerSocketFactory', 'SocketFactory'),

		'java/java/javax/net',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/net/{FNAME}.html'
	);


	//javax.net.ssl   
	$context->addKeywordGroup(array(
			'CertPathTrustManagerParameters', 'HandshakeCompletedEvent', 'HandshakeCompletedListener', 
			'HostnameVerifier', 'HttpsURLConnection', 'KeyManager', 
			'KeyManagerFactory', 'KeyManagerFactorySpi', 'KeyStoreBuilderParameters', 
			'ManagerFactoryParameters', 'SSLContext', 'SSLContextSpi', 
			'SSLEngine', 'SSLEngineResult', 'SSLEngineResult.HandshakeStatus', 
			'SSLEngineResult.Status', 'SSLException', 'SSLHandshakeException', 
			'SSLKeyException', 'SSLPeerUnverifiedException', 'SSLPermission', 
			'SSLProtocolException', 'SSLServerSocket', 'SSLServerSocketFactory', 
			'SSLSession', 'SSLSessionBindingEvent', 'SSLSessionBindingListener', 
			'SSLSessionContext', 'SSLSocket', 'SSLSocketFactory', 
			'TrustManager', 'TrustManagerFactory', 'TrustManagerFactorySpi', 
			'X509ExtendedKeyManager', 'X509KeyManager', 'X509TrustManager'),

		'java/java/javax/net/ssl',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/net/ssl/{FNAME}.html'
	);


	//javax.print
	$context->addKeywordGroup(array(
			'AttributeException', 'CancelablePrintJob', 'Doc', 
			'DocFlavor', 'DocFlavor.BYTE_ARRAY', 'DocFlavor.CHAR_ARRAY', 
			'DocFlavor.INPUT_STREAM', 'DocFlavor.READER', 'DocFlavor.SERVICE_FORMATTED', 
			'DocFlavor.STRING', 'DocFlavor.URL', 'DocPrintJob', 
			'FlavorException', 'MultiDoc', 'MultiDocPrintJob', 
			'MultiDocPrintService', 'PrintException', 'PrintService', 
			'PrintServiceLookup', 'ServiceUI', 'ServiceUIFactory', 
			'SimpleDoc', 'StreamPrintService', 'StreamPrintServiceFactory', 
			'URIException'),

		'java/java/javax/print',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/print/{FNAME}.html'
	);


	//javax.print.attribute   
	$context->addKeywordGroup(array(
			'Attribute', 'AttributeSet', 'AttributeSetUtilities', 
			'DateTimeSyntax', 'DocAttribute', 'DocAttributeSet', 
			'EnumSyntax', 'HashAttributeSet', 'HashDocAttributeSet', 
			'HashPrintJobAttributeSet', 'HashPrintRequestAttributeSet', 'HashPrintServiceAttributeSet', 
			'IntegerSyntax', 'PrintJobAttribute', 'PrintJobAttributeSet', 
			'PrintRequestAttribute', 'PrintRequestAttributeSet', 'PrintServiceAttribute', 
			'PrintServiceAttributeSet', 'ResolutionSyntax', 'SetOfIntegerSyntax', 
			'Size2DSyntax', 'SupportedValuesAttribute', 'TextSyntax', 
			'URISyntax', 'UnmodifiableSetException'),

		'java/java/javax/print/attribute',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/print/attribute/{FNAME}.html'
	);


	//javax.print.attribute.standard  
	$context->addKeywordGroup(array(
			'Chromaticity', 'ColorSupported', 'Compression', 
			'Copies', 'CopiesSupported', 'DateTimeAtCompleted', 
			'DateTimeAtCreation', 'DateTimeAtProcessing', 'Destination', 
			'DocumentName', 'Fidelity', 'Finishings', 
			'JobHoldUntil', 'JobImpressions', 'JobImpressionsCompleted', 
			'JobImpressionsSupported', 'JobKOctets', 'JobKOctetsProcessed', 
			'JobKOctetsSupported', 'JobMediaSheets', 'JobMediaSheetsCompleted', 
			'JobMediaSheetsSupported', 'JobMessageFromOperator', 'JobName', 
			'JobOriginatingUserName', 'JobPriority', 'JobPrioritySupported', 
			'JobSheets', 'JobState', 'JobStateReason', 
			'JobStateReasons', 'Media', 'MediaName', 
			'MediaPrintableArea', 'MediaSize', 'MediaSize.Engineering', 
			'MediaSize.ISO', 'MediaSize.JIS', 'MediaSize.NA', 
			'MediaSize.Other', 'MediaSizeName', 'MediaTray', 
			'MultipleDocumentHandling', 'NumberOfDocuments', 'NumberOfInterveningJobs', 
			'NumberUp', 'NumberUpSupported', 'OrientationRequested', 
			'OutputDeviceAssigned', 'PDLOverrideSupported', 'PageRanges', 
			'PagesPerMinute', 'PagesPerMinuteColor', 'PresentationDirection', 
			'PrintQuality', 'PrinterInfo', 'PrinterIsAcceptingJobs', 
			'PrinterLocation', 'PrinterMakeAndModel', 'PrinterMessageFromOperator', 
			'PrinterMoreInfo', 'PrinterMoreInfoManufacturer', 'PrinterName', 
			'PrinterResolution', 'PrinterState', 'PrinterStateReason', 
			'PrinterStateReasons', 'PrinterURI', 'QueuedJobCount', 
			'ReferenceUriSchemesSupported', 'RequestingUserName', 'Severity', 
			'SheetCollate', 'Sides'),

		'java/java/javax/print/attribute/standard',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/print/attribute/standard/{FNAME}.html'
	);


	//javax.print.event  
	$context->addKeywordGroup(array(
			'PrintEvent', 'PrintJobAdapter', 'PrintJobAttributeEvent', 
			'PrintJobAttributeListener', 'PrintJobEvent', 'PrintJobListener', 
			'PrintServiceAttributeEvent', 'PrintServiceAttributeListener'),

		'java/java/javax/print/event',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/print/event/{FNAME}.html'
	);


	//javax.rmi
	$context->addKeywordGroup(array(
			'PortableRemoteObject'),

		'java/java/javax/rmi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/rmi/{FNAME}.html'
	);


	//javax.rmi.CORBA  
	$context->addKeywordGroup(array(
			'ClassDesc', 'PortableRemoteObjectDelegate', 'Stub', 
			'StubDelegate', 'Tie', 'Util', 
			'UtilDelegate', 'ValueHandler', 'ValueHandlerMultiFormat'),

		'java/java/javax/rmi/CORBA',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/rmi/CORBA/{FNAME}.html'
	);


	//javax.rmi.ssl   
	$context->addKeywordGroup(array(
			'SslRMIClientSocketFactory', 'SslRMIServerSocketFactory'),

		'java/java/javax/rmi/ssl',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/rmi/ssl/{FNAME}.html'
	);


	//javax.security.auth   
	$context->addKeywordGroup(array(
			'AuthPermission', 'DestroyFailedException', 'Destroyable', 
			'Policy', 'PrivateCredentialPermission', 'RefreshFailedException', 
			'Refreshable', 'Subject', 'SubjectDomainCombiner'),

		'java/java/javax/security/auth',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/{FNAME}.html'
	);


	//javax.security.auth.callback   
	$context->addKeywordGroup(array(
			'Callback', 'CallbackHandler', 'ChoiceCallback', 
			'ConfirmationCallback', 'LanguageCallback', 'NameCallback', 
			'PasswordCallback', 'TextInputCallback', 'TextOutputCallback', 
			'UnsupportedCallbackException'),

		'java/java/javax/security/auth/callback',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/callback/{FNAME}.html'
	);


	//javax.security.auth.kerberos   
	$context->addKeywordGroup(array(
			'DelegationPermission', 'KerberosKey', 'KerberosPrincipal', 
			'KerberosTicket', 'ServicePermission'),

		'java/java/javax/security/auth/kerberos',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/kerberos/{FNAME}.html'
	);


	//javax.security.auth.login   
	$context->addKeywordGroup(array(
			'AccountException', 'AccountExpiredException', 'AccountLockedException', 
			'AccountNotFoundException', 'AppConfigurationEntry', 'AppConfigurationEntry.LoginModuleControlFlag', 
			'Configuration', 'CredentialException', 'CredentialExpiredException', 
			'CredentialNotFoundException', 'FailedLoginException', 'LoginContext', 
			'LoginException'),

		'java/java/javax/security/auth/login',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/login/{FNAME}.html'
	);


	//javax.security.auth.spi  
	$context->addKeywordGroup(array(
			'LoginModule'),

		'java/java/javax/security/auth/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/spi/{FNAME}.html'
	);


	//javax.security.auth.x500   
	$context->addKeywordGroup(array(
			'X500Principal', 'X500PrivateCredential'),

		'java/java/javax/security/auth/x500',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/auth/x500/{FNAME}.html'
	);


	//javax.security.cert  
	$context->addKeywordGroup(array(
			'Certificate', 'CertificateEncodingException', 'CertificateException', 
			'CertificateExpiredException', 'CertificateNotYetValidException', 'CertificateParsingException', 
			'X509Certificate'),

		'java/java/javax/security/cert',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/cert/{FNAME}.html'
	);


	//javax.security.sasl   
	$context->addKeywordGroup(array(
			'AuthenticationException', 'AuthorizeCallback', 'RealmCallback', 
			'RealmChoiceCallback', 'Sasl', 'SaslClient', 
			'SaslClientFactory', 'SaslException', 'SaslServer', 
			'SaslServerFactory'),

		'java/java/javax/security/sasl',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/security/sasl/{FNAME}.html'
	);


	//javax.sound.midi   
	$context->addKeywordGroup(array(
			'ControllerEventListener', 'Instrument', 'InvalidMidiDataException', 
			'MetaEventListener', 'MetaMessage', 'MidiChannel', 
			'MidiDevice', 'MidiDevice.Info', 'MidiEvent', 
			'MidiFileFormat', 'MidiMessage', 'MidiSystem', 
			'MidiUnavailableException', 'Patch', 'Receiver', 
			'Sequence', 'Sequencer', 'Sequencer.SyncMode', 
			'ShortMessage', 'Soundbank', 'SoundbankResource', 
			'Synthesizer', 'SysexMessage', 'Track', 
			'Transmitter', 'VoiceStatus'),

		'java/java/javax/sound/midi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sound/midi/{FNAME}.html'
	);


	//javax.sound.midi.spi  
	$context->addKeywordGroup(array(
			'MidiDeviceProvider', 'MidiFileReader', 'MidiFileWriter', 
			'SoundbankReader'),

		'java/java/javax/sound/midi/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sound/midi/spi/{FNAME}.html'
	);


	//javax.sound.sampled   
	$context->addKeywordGroup(array(
			'AudioFileFormat', 'AudioFileFormat.Type', 'AudioFormat', 
			'AudioFormat.Encoding', 'AudioInputStream', 'AudioPermission', 
			'AudioSystem', 'BooleanControl', 'BooleanControl.Type', 
			'Clip', 'CompoundControl', 'CompoundControl.Type', 
			'Control', 'Control.Type', 'DataLine', 
			'DataLine.Info', 'EnumControl', 'EnumControl.Type', 
			'FloatControl', 'FloatControl.Type', 'Line', 
			'Line.Info', 'LineEvent', 'LineEvent.Type', 
			'LineListener', 'LineUnavailableException', 'Mixer', 
			'Mixer.Info', 'Port', 'Port.Info', 
			'ReverbType', 'SourceDataLine', 'TargetDataLine', 
			'UnsupportedAudioFileException'),

		'java/java/javax/sound/sampled',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sound/sampled/{FNAME}.html'
	);


	//javax.sound.sampled.spi   
	$context->addKeywordGroup(array(
			'AudioFileReader', 'AudioFileWriter', 'FormatConversionProvider', 
			'MixerProvider'),

		'java/java/javax/sound/sampled/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sound/sampled/spi/{FNAME}.html'
	);


	//javax.sql  
	$context->addKeywordGroup(array(
			'ConnectionEvent', 'ConnectionEventListener', 'ConnectionPoolDataSource', 
			'DataSource', 'PooledConnection', 'RowSet', 
			'RowSetEvent', 'RowSetInternal', 'RowSetListener', 
			'RowSetMetaData', 'RowSetReader', 'RowSetWriter', 
			'XAConnection', 'XADataSource'),

		'java/java/javax/sql',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sql/{FNAME}.html'
	);


	//javax.sql.rowset   
	$context->addKeywordGroup(array(
			'BaseRowSet', 'CachedRowSet', 'FilteredRowSet', 
			'JdbcRowSet', 'JoinRowSet', 'Joinable', 
			'Predicate', 'RowSetMetaDataImpl', 'RowSetWarning', 
			'WebRowSet'),

		'java/java/javax/sql/rowset',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sql/rowset/{FNAME}.html'
	);


	//javax.sql.rowset.serial   
	$context->addKeywordGroup(array(
			'SQLInputImpl', 'SQLOutputImpl', 'SerialArray', 
			'SerialBlob', 'SerialClob', 'SerialDatalink', 
			'SerialException', 'SerialJavaObject', 'SerialRef', 
			'SerialStruct'),

		'java/java/javax/sql/rowset/serial',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sql/rowset/serial/{FNAME}.html'
	);


	//javax.sql.rowset.spi   
	$context->addKeywordGroup(array(
			'SyncFactory', 'SyncFactoryException', 'SyncProvider', 
			'SyncProviderException', 'SyncResolver', 'TransactionalWriter', 
			'XmlReader', 'XmlWriter'),

		'java/java/javax/sql/rowset/spi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/sql/rowset/spi/{FNAME}.html'
	);


	//javax.swing  
	$context->addKeywordGroup(array(
			'AbstractAction', 'AbstractButton', 'AbstractCellEditor', 
			'AbstractListModel', 'AbstractSpinnerModel', 'Action', 
			'ActionMap', 'BorderFactory', 'BoundedRangeModel', 
			'Box', 'Box.Filler', 'BoxLayout', 
			'ButtonGroup', 'ButtonModel', 'CellEditor', 
			'CellRendererPane', 'ComboBoxEditor', 'ComboBoxModel', 
			'ComponentInputMap', 'DebugGraphics', 'DefaultBoundedRangeModel', 
			'DefaultButtonModel', 'DefaultCellEditor', 'DefaultComboBoxModel', 
			'DefaultDesktopManager', 'DefaultFocusManager', 'DefaultListCellRenderer', 
			'DefaultListCellRenderer.UIResource', 'DefaultListModel', 'DefaultListSelectionModel', 
			'DefaultSingleSelectionModel', 'DesktopManager', 'FocusManager', 
			'GrayFilter', 'Icon', 'ImageIcon', 
			'InputMap', 'InputVerifier', 'InternalFrameFocusTraversalPolicy', 
			'JApplet', 'JButton', 'JCheckBox', 
			'JCheckBoxMenuItem', 'JColorChooser', 'JComboBox', 
			'JComboBox.KeySelectionManager', 'JComponent', 'JDesktopPane', 
			'JDialog', 'JEditorPane', 'JFileChooser', 
			'JFormattedTextField', 'JFormattedTextField.AbstractFormatter', 'JFormattedTextField.AbstractFormatterFactory', 
			'JFrame', 'JInternalFrame', 'JInternalFrame.JDesktopIcon', 
			'JLabel', 'JLayeredPane', 'JList', 
			'JMenu', 'JMenuBar', 'JMenuItem', 
			'JOptionPane', 'JPanel', 'JPasswordField', 
			'JPopupMenu', 'JPopupMenu.Separator', 'JProgressBar', 
			'JRadioButton', 'JRadioButtonMenuItem', 'JRootPane', 
			'JScrollBar', 'JScrollPane', 'JSeparator', 
			'JSlider', 'JSpinner', 'JSpinner.DateEditor', 
			'JSpinner.DefaultEditor', 'JSpinner.ListEditor', 'JSpinner.NumberEditor', 
			'JSplitPane', 'JTabbedPane', 'JTable', 
			'JTable.PrintMode', 'JTextArea', 'JTextField', 
			'JTextPane', 'JToggleButton', 'JToggleButton.ToggleButtonModel', 
			'JToolBar', 'JToolBar.Separator', 'JToolTip', 
			'JTree', 'JTree.DynamicUtilTreeNode', 'JTree.EmptySelectionModel', 
			'JViewport', 'JWindow', 'KeyStroke', 
			'LayoutFocusTraversalPolicy', 'ListCellRenderer', 'ListModel', 
			'ListSelectionModel', 'LookAndFeel', 'MenuElement', 
			'MenuSelectionManager', 'MutableComboBoxModel', 'OverlayLayout', 
			'Popup', 'PopupFactory', 'ProgressMonitor', 
			'ProgressMonitorInputStream', 'Renderer', 'RepaintManager', 
			'RootPaneContainer', 'ScrollPaneConstants', 'ScrollPaneLayout', 
			'ScrollPaneLayout.UIResource', 'Scrollable', 'SingleSelectionModel', 
			'SizeRequirements', 'SizeSequence', 'SortingFocusTraversalPolicy', 
			'SpinnerDateModel', 'SpinnerListModel', 'SpinnerModel', 
			'SpinnerNumberModel', 'Spring', 'SpringLayout', 
			'SpringLayout.Constraints', 'SwingConstants', 'SwingUtilities', 
			'Timer', 'ToolTipManager', 'TransferHandler', 
			'UIDefaults', 'UIDefaults.ActiveValue', 'UIDefaults.LazyInputMap', 
			'UIDefaults.LazyValue', 'UIDefaults.ProxyLazyValue', 'UIManager', 
			'UIManager.LookAndFeelInfo', 'UnsupportedLookAndFeelException', 'ViewportLayout', 
			'WindowConstants'),

		'java/java/javax/swing',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/{FNAME}.html'
	);


	//javax.swing.border   
	$context->addKeywordGroup(array(
			'AbstractBorder', 'BevelBorder', 'Border', 
			'CompoundBorder', 'EmptyBorder', 'EtchedBorder', 
			'LineBorder', 'MatteBorder', 'SoftBevelBorder', 
			'TitledBorder'),

		'java/java/javax/swing/border',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/border/{FNAME}.html'
	);


	//javax.swing.colorchooser   
	$context->addKeywordGroup(array(
			'AbstractColorChooserPanel', 'ColorChooserComponentFactory', 'ColorSelectionModel', 
			'DefaultColorSelectionModel'),

		'java/java/javax/swing/colorchooser',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/colorchooser/{FNAME}.html'
	);


	//javax.swing.event   
	$context->addKeywordGroup(array(
			'AncestorEvent', 'AncestorListener', 'CaretEvent', 
			'CaretListener', 'CellEditorListener', 'ChangeEvent', 
			'ChangeListener', 'DocumentEvent', 'DocumentEvent.ElementChange', 
			'DocumentEvent.EventType', 'DocumentListener', 'EventListenerList', 
			'HyperlinkEvent', 'HyperlinkEvent.EventType', 'HyperlinkListener', 
			'InternalFrameAdapter', 'InternalFrameEvent', 'InternalFrameListener', 
			'ListDataEvent', 'ListDataListener', 'ListSelectionEvent', 
			'ListSelectionListener', 'MenuDragMouseEvent', 'MenuDragMouseListener', 
			'MenuEvent', 'MenuKeyEvent', 'MenuKeyListener', 
			'MenuListener', 'MouseInputAdapter', 'MouseInputListener', 
			'PopupMenuEvent', 'PopupMenuListener', 'SwingPropertyChangeSupport', 
			'TableColumnModelEvent', 'TableColumnModelListener', 'TableModelEvent', 
			'TableModelListener', 'TreeExpansionEvent', 'TreeExpansionListener', 
			'TreeModelEvent', 'TreeModelListener', 'TreeSelectionEvent', 
			'TreeSelectionListener', 'TreeWillExpandListener', 'UndoableEditEvent', 
			'UndoableEditListener'),

		'java/java/javax/swing/event',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/event/{FNAME}.html'
	);


	//javax.swing.filechooser   
	$context->addKeywordGroup(array(
			'FileFilter', 'FileSystemView', 'FileView'),

		'java/java/javax/swing/filechooser',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/filechooser/{FNAME}.html'
	);


	//javax.swing.plaf   
	$context->addKeywordGroup(array(
			'ActionMapUIResource', 'BorderUIResource', 'BorderUIResource.BevelBorderUIResource', 
			'BorderUIResource.CompoundBorderUIResource', 'BorderUIResource.EmptyBorderUIResource', 'BorderUIResource.EtchedBorderUIResource', 
			'BorderUIResource.LineBorderUIResource', 'BorderUIResource.MatteBorderUIResource', 'BorderUIResource.TitledBorderUIResource', 
			'ButtonUI', 'ColorChooserUI', 'ColorUIResource', 
			'ComboBoxUI', 'ComponentInputMapUIResource', 'ComponentUI', 
			'DesktopIconUI', 'DesktopPaneUI', 'DimensionUIResource', 
			'FileChooserUI', 'FontUIResource', 'IconUIResource', 
			'InputMapUIResource', 'InsetsUIResource', 'InternalFrameUI', 
			'LabelUI', 'ListUI', 'MenuBarUI', 
			'MenuItemUI', 'OptionPaneUI', 'PanelUI', 
			'PopupMenuUI', 'ProgressBarUI', 'RootPaneUI', 
			'ScrollBarUI', 'ScrollPaneUI', 'SeparatorUI', 
			'SliderUI', 'SpinnerUI', 'SplitPaneUI', 
			'TabbedPaneUI', 'TableHeaderUI', 'TableUI', 
			'TextUI', 'ToolBarUI', 'ToolTipUI', 
			'TreeUI', 'UIResource', 'ViewportUI'),

		'java/java/javax/swing/plaf',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/plaf/{FNAME}.html'
	);


	//javax.swing.plaf.basic   
	$context->addKeywordGroup(array(
			'BasicArrowButton', 'BasicBorders', 'BasicBorders.ButtonBorder', 
			'BasicBorders.FieldBorder', 'BasicBorders.MarginBorder', 'BasicBorders.MenuBarBorder', 
			'BasicBorders.RadioButtonBorder', 'BasicBorders.RolloverButtonBorder', 'BasicBorders.SplitPaneBorder', 
			'BasicBorders.ToggleButtonBorder', 'BasicButtonListener', 'BasicButtonUI', 
			'BasicCheckBoxMenuItemUI', 'BasicCheckBoxUI', 'BasicColorChooserUI', 
			'BasicComboBoxEditor', 'BasicComboBoxEditor.UIResource', 'BasicComboBoxRenderer', 
			'BasicComboBoxRenderer.UIResource', 'BasicComboBoxUI', 'BasicComboPopup', 
			'BasicDesktopIconUI', 'BasicDesktopPaneUI', 'BasicDirectoryModel', 
			'BasicEditorPaneUI', 'BasicFileChooserUI', 'BasicFormattedTextFieldUI', 
			'BasicGraphicsUtils', 'BasicHTML', 'BasicIconFactory', 
			'BasicInternalFrameTitlePane', 'BasicInternalFrameUI', 'BasicLabelUI', 
			'BasicListUI', 'BasicLookAndFeel', 'BasicMenuBarUI', 
			'BasicMenuItemUI', 'BasicMenuUI', 'BasicOptionPaneUI', 
			'BasicOptionPaneUI.ButtonAreaLayout', 'BasicPanelUI', 'BasicPasswordFieldUI', 
			'BasicPopupMenuSeparatorUI', 'BasicPopupMenuUI', 'BasicProgressBarUI', 
			'BasicRadioButtonMenuItemUI', 'BasicRadioButtonUI', 'BasicRootPaneUI', 
			'BasicScrollBarUI', 'BasicScrollPaneUI', 'BasicSeparatorUI', 
			'BasicSliderUI', 'BasicSpinnerUI', 'BasicSplitPaneDivider', 
			'BasicSplitPaneUI', 'BasicTabbedPaneUI', 'BasicTableHeaderUI', 
			'BasicTableUI', 'BasicTextAreaUI', 'BasicTextFieldUI', 
			'BasicTextPaneUI', 'BasicTextUI', 'BasicTextUI.BasicCaret', 
			'BasicTextUI.BasicHighlighter', 'BasicToggleButtonUI', 'BasicToolBarSeparatorUI', 
			'BasicToolBarUI', 'BasicToolTipUI', 'BasicTreeUI', 
			'BasicViewportUI', 'ComboPopup', 'DefaultMenuLayout'),

		'java/java/javax/swing/plaf/basic',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/plaf/basic/{FNAME}.html'
	);


	//javax.swing.plaf.metal  
	$context->addKeywordGroup(array(
			'DefaultMetalTheme', 'MetalBorders', 'MetalBorders.ButtonBorder', 
			'MetalBorders.Flush3DBorder', 'MetalBorders.InternalFrameBorder', 'MetalBorders.MenuBarBorder', 
			'MetalBorders.MenuItemBorder', 'MetalBorders.OptionDialogBorder', 'MetalBorders.PaletteBorder', 
			'MetalBorders.PopupMenuBorder', 'MetalBorders.RolloverButtonBorder', 'MetalBorders.ScrollPaneBorder', 
			'MetalBorders.TableHeaderBorder', 'MetalBorders.TextFieldBorder', 'MetalBorders.ToggleButtonBorder', 
			'MetalBorders.ToolBarBorder', 'MetalButtonUI', 'MetalCheckBoxIcon', 
			'MetalCheckBoxUI', 'MetalComboBoxButton', 'MetalComboBoxEditor', 
			'MetalComboBoxEditor.UIResource', 'MetalComboBoxIcon', 'MetalComboBoxUI', 
			'MetalDesktopIconUI', 'MetalFileChooserUI', 'MetalIconFactory', 
			'MetalIconFactory.FileIcon16', 'MetalIconFactory.FolderIcon16', 'MetalIconFactory.PaletteCloseIcon', 
			'MetalIconFactory.TreeControlIcon', 'MetalIconFactory.TreeFolderIcon', 'MetalIconFactory.TreeLeafIcon', 
			'MetalInternalFrameTitlePane', 'MetalInternalFrameUI', 'MetalLabelUI', 
			'MetalLookAndFeel', 'MetalMenuBarUI', 'MetalPopupMenuSeparatorUI', 
			'MetalProgressBarUI', 'MetalRadioButtonUI', 'MetalRootPaneUI', 
			'MetalScrollBarUI', 'MetalScrollButton', 'MetalScrollPaneUI', 
			'MetalSeparatorUI', 'MetalSliderUI', 'MetalSplitPaneUI', 
			'MetalTabbedPaneUI', 'MetalTextFieldUI', 'MetalTheme', 
			'MetalToggleButtonUI', 'MetalToolBarUI', 'MetalToolTipUI', 
			'MetalTreeUI', 'OceanTheme'),

		'java/java/javax/swing/plaf/metal',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/plaf/metal/{FNAME}.html'
	);


	//javax.swing.plaf.multi   
	$context->addKeywordGroup(array(
			'MultiButtonUI', 'MultiColorChooserUI', 'MultiComboBoxUI', 
			'MultiDesktopIconUI', 'MultiDesktopPaneUI', 'MultiFileChooserUI', 
			'MultiInternalFrameUI', 'MultiLabelUI', 'MultiListUI', 
			'MultiLookAndFeel', 'MultiMenuBarUI', 'MultiMenuItemUI', 
			'MultiOptionPaneUI', 'MultiPanelUI', 'MultiPopupMenuUI', 
			'MultiProgressBarUI', 'MultiRootPaneUI', 'MultiScrollBarUI', 
			'MultiScrollPaneUI', 'MultiSeparatorUI', 'MultiSliderUI', 
			'MultiSpinnerUI', 'MultiSplitPaneUI', 'MultiTabbedPaneUI', 
			'MultiTableHeaderUI', 'MultiTableUI', 'MultiTextUI', 
			'MultiToolBarUI', 'MultiToolTipUI', 'MultiTreeUI', 
			'MultiViewportUI'),

		'java/java/javax/swing/plaf/multi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/plaf/multi/{FNAME}.html'
	);


	//javax.swing.plaf.synth   
	$context->addKeywordGroup(array(
			'ColorType', 'Region', 'SynthConstants', 
			'SynthContext', 'SynthGraphicsUtils', 'SynthLookAndFeel', 
			'SynthPainter', 'SynthStyle', 'SynthStyleFactory'),

		'java/java/javax/swing/plaf/synth',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/plaf/synth/{FNAME}.html'
	);


	//javax.swing.table
	$context->addKeywordGroup(array(
			'AbstractTableModel', 'DefaultTableCellRenderer', 'DefaultTableCellRenderer.UIResource', 
			'DefaultTableColumnModel', 'DefaultTableModel', 'JTableHeader', 
			'TableCellEditor', 'TableCellRenderer', 'TableColumn', 
			'TableColumnModel', 'TableModel'),

		'java/java/javax/swing/table',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/table/{FNAME}.html'
	);


	//javax.swing.text   
	$context->addKeywordGroup(array(
			'AbstractDocument', 'AbstractDocument.AttributeContext', 'AbstractDocument.Content', 
			'AbstractDocument.ElementEdit', 'AbstractWriter', 'AsyncBoxView', 
			'AttributeSet', 'AttributeSet.CharacterAttribute', 'AttributeSet.ColorAttribute', 
			'AttributeSet.FontAttribute', 'AttributeSet.ParagraphAttribute', 'BadLocationException', 
			'BoxView', 'Caret', 'ChangedCharSetException', 
			'ComponentView', 'CompositeView', 'DateFormatter', 
			'DefaultCaret', 'DefaultEditorKit', 'DefaultEditorKit.BeepAction', 
			'DefaultEditorKit.CopyAction', 'DefaultEditorKit.CutAction', 'DefaultEditorKit.DefaultKeyTypedAction', 
			'DefaultEditorKit.InsertBreakAction', 'DefaultEditorKit.InsertContentAction', 'DefaultEditorKit.InsertTabAction', 
			'DefaultEditorKit.PasteAction', 'DefaultFormatter', 'DefaultFormatterFactory', 
			'DefaultHighlighter', 'DefaultHighlighter.DefaultHighlightPainter', 'DefaultStyledDocument', 
			'DefaultStyledDocument.AttributeUndoableEdit', 'DefaultStyledDocument.ElementSpec', 'DefaultTextUI', 
			'Document', 'DocumentFilter', 'DocumentFilter.FilterBypass', 
			'EditorKit', 'Element', 'ElementIterator', 
			'FieldView', 'FlowView', 'FlowView.FlowStrategy', 
			'GapContent', 'GlyphView', 'GlyphView.GlyphPainter', 
			'Highlighter', 'Highlighter.Highlight', 'Highlighter.HighlightPainter', 
			'IconView', 'InternationalFormatter', 'JTextComponent', 
			'JTextComponent.KeyBinding', 'Keymap', 'LabelView', 
			'LayeredHighlighter', 'LayeredHighlighter.LayerPainter', 'LayoutQueue', 
			'MaskFormatter', 'MutableAttributeSet', 'NavigationFilter', 
			'NavigationFilter.FilterBypass', 'NumberFormatter', 'ParagraphView', 
			'PasswordView', 'PlainDocument', 'PlainView', 
			'Position', 'Position.Bias', 'Segment', 
			'SimpleAttributeSet', 'StringContent', 'Style', 
			'StyleConstants', 'StyleConstants.CharacterConstants', 'StyleConstants.ColorConstants', 
			'StyleConstants.FontConstants', 'StyleConstants.ParagraphConstants', 'StyleContext', 
			'StyledDocument', 'StyledEditorKit', 'StyledEditorKit.AlignmentAction', 
			'StyledEditorKit.BoldAction', 'StyledEditorKit.FontFamilyAction', 'StyledEditorKit.FontSizeAction', 
			'StyledEditorKit.ForegroundAction', 'StyledEditorKit.ItalicAction', 'StyledEditorKit.StyledTextAction', 
			'StyledEditorKit.UnderlineAction', 'TabExpander', 'TabSet', 
			'TabStop', 'TabableView', 'TableView', 
			'TextAction', 'Utilities', 'View', 
			'ViewFactory', 'WrappedPlainView', 'ZoneView'),

		'java/java/javax/swing/text',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/text/{FNAME}.html'
	);


	//javax.swing.text.html   
	$context->addKeywordGroup(array(
			'BlockView', 'CSS', 'CSS.Attribute', 
			'FormSubmitEvent', 'FormSubmitEvent.MethodType', 'FormView', 
			'HTML', 'HTML.Attribute', 'HTML.Tag', 
			'HTML.UnknownTag', 'HTMLDocument', 'HTMLDocument.Iterator', 
			'HTMLEditorKit', 'HTMLEditorKit.HTMLFactory', 'HTMLEditorKit.HTMLTextAction', 
			'HTMLEditorKit.InsertHTMLTextAction', 'HTMLEditorKit.LinkController', 'HTMLEditorKit.Parser', 
			'HTMLEditorKit.ParserCallback', 'HTMLFrameHyperlinkEvent', 'HTMLWriter', 
			'ImageView', 'InlineView', 'ListView', 
			'MinimalHTMLWriter', 'ObjectView', 'Option', 
			'ParagraphView', 'StyleSheet', 'StyleSheet.BoxPainter', 
			'StyleSheet.ListPainter'),

		'java/java/javax/swing/text/html',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/text/html/{FNAME}.html'
	);


	//javax.swing.text.html.parser   
	$context->addKeywordGroup(array(
			'AttributeList', 'ContentModel', 'DTD', 
			'DTDConstants', 'DocumentParser', 'Element', 
			'Entity', 'Parser', 'ParserDelegator', 
			'TagElement'),

		'java/java/javax/swing/text/html/parser',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/text/html/parser/{FNAME}.html'
	);


	//javax.swing.text.rtf   
	$context->addKeywordGroup(array(
			'RTFEditorKit'),

		'java/java/javax/swing/text/rtf',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/text/rtf/{FNAME}.html'
	);


	//javax.swing.tree   
	$context->addKeywordGroup(array(
			'AbstractLayoutCache', 'AbstractLayoutCache.NodeDimensions', 'DefaultMutableTreeNode', 
			'DefaultTreeCellEditor', 'DefaultTreeCellRenderer', 'DefaultTreeModel', 
			'DefaultTreeSelectionModel', 'ExpandVetoException', 'FixedHeightLayoutCache', 
			'MutableTreeNode', 'RowMapper', 'TreeCellEditor', 
			'TreeCellRenderer', 'TreeModel', 'TreeNode', 
			'TreePath', 'TreeSelectionModel', 'VariableHeightLayoutCache'),

		'java/java/javax/swing/tree',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/tree/{FNAME}.html'
	);


	//javax.swing.undo
	$context->addKeywordGroup(array(
			'AbstractUndoableEdit', 'CannotRedoException', 'CannotUndoException', 
			'CompoundEdit', 'StateEdit', 'StateEditable', 
			'UndoManager', 'UndoableEdit', 'UndoableEditSupport'),

		'java/java/javax/swing/undo',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/swing/undo/{FNAME}.html'
	);


	//javax.transaction
	$context->addKeywordGroup(array(
			'InvalidTransactionException', 'TransactionRequiredException', 'TransactionRolledbackException'),

		'java/java/javax/transaction',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/transaction/{FNAME}.html'
	);


	//javax.transaction.xa   
	$context->addKeywordGroup(array(
			'XAException', 'XAResource', 'Xid'),

		'java/java/javax/transaction/xa',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/transaction/xa/{FNAME}.html'
	);


	//javax.xml   
	$context->addKeywordGroup(array(
			'XMLConstants'),

		'java/java/javax/xml',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/{FNAME}.html'
	);


	//javax.xml.datatype   
	$context->addKeywordGroup(array(
			'DatatypeConfigurationException', 'DatatypeConstants', 'DatatypeConstants.Field', 
			'DatatypeFactory', 'Duration', 'NamespaceContext', 
			'QName', 'XMLGregorianCalendar', 'javax.xml.namespace'),

		'java/java/javax/xml/datatype',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/datatype/{FNAME}.html'
	);


	//javax.xml.parsers  
	$context->addKeywordGroup(array(
			'DocumentBuilder', 'DocumentBuilderFactory', 'FactoryConfigurationError', 
			'ParserConfigurationException', 'SAXParser', 'SAXParserFactory'),

		'java/java/javax/xml/parsers',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/parsers/{FNAME}.html'
	);


	//javax.xml.transform   
	$context->addKeywordGroup(array(
			'ErrorListener', 'OutputKeys', 'Result', 
			'Source', 'SourceLocator', 'Templates', 
			'Transformer', 'TransformerConfigurationException', 'TransformerException', 
			'TransformerFactory', 'TransformerFactoryConfigurationError', 'URIResolver'),

		'java/java/javax/xml/transform',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/transform/{FNAME}.html'
	);


	//javax.xml.transform.dom 
	$context->addKeywordGroup(array(
			'DOMLocator', 'DOMResult', 'DOMSource'),

		'java/java/javax/xml/transform/dom',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/transform/dom/{FNAME}.html'
	);


	//javax.xml.transform.sax   
	$context->addKeywordGroup(array(
			'SAXResult', 'SAXSource', 'SAXTransformerFactory', 
			'TemplatesHandler', 'TransformerHandler'),

		'java/java/javax/xml/transform/sax',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/transform/sax/{FNAME}.html'
	);


	//javax.xml.transform.stream   
	$context->addKeywordGroup(array(
			'StreamResult', 'StreamSource'),

		'java/java/javax/xml/transform/stream',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/transform/stream/{FNAME}.html'
	);


	//javax.xml.validation   
	$context->addKeywordGroup(array(
			'Schema', 'SchemaFactory', 'SchemaFactoryLoader', 
			'TypeInfoProvider', 'Validator', 'ValidatorHandler'),

		'java/java/javax/xml/validation',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/validation/{FNAME}.html'
	);


	//javax.xml.xpath   
	$context->addKeywordGroup(array(
			'XPath', 'XPathConstants', 'XPathException', 
			'XPathExpression', 'XPathExpressionException', 'XPathFactory', 
			'XPathFactoryConfigurationException', 'XPathFunction', 'XPathFunctionException', 
			'XPathFunctionResolver', 'XPathVariableResolver'),

		'java/java/javax/xml/xpath',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/javax/xml/xpath/{FNAME}.html'
	);


	//org.ietf.jgss
	$context->addKeywordGroup(array(
			'ChannelBinding', 'GSSContext', 'GSSCredential', 
			'GSSException', 'GSSManager', 'GSSName', 
			'MessageProp', 'Oid'),

		'java/java/org/ietf/jgss',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/ietf/jgss/{FNAME}.html'
	);


	//org.omg.CORBA   
	$context->addKeywordGroup(array(
			'ACTIVITY_COMPLETED', 'ACTIVITY_REQUIRED', 'ARG_IN', 
			'ARG_INOUT', 'ARG_OUT', 'Any', 
			'AnyHolder', 'AnySeqHelper', 'AnySeqHolder', 
			'BAD_CONTEXT', 'BAD_INV_ORDER', 'BAD_OPERATION', 
			'BAD_PARAM', 'BAD_POLICY', 'BAD_POLICY_TYPE', 
			'BAD_POLICY_VALUE', 'BAD_QOS', 'BAD_TYPECODE', 
			'BooleanHolder', 'BooleanSeqHelper', 'BooleanSeqHolder', 
			'Bounds', 'ByteHolder', 'CODESET_INCOMPATIBLE', 
			'COMM_FAILURE', 'CTX_RESTRICT_SCOPE', 'CharHolder', 
			'CharSeqHelper', 'CharSeqHolder', 'CompletionStatus', 
			'CompletionStatusHelper', 'Context', 'ContextList', 
			'Current', 'CurrentHelper', 'CurrentHolder', 
			'CurrentOperations', 'CustomMarshal', 'DATA_CONVERSION', 
			'DataInputStream', 'DataOutputStream', 'DefinitionKind', 
			'DefinitionKindHelper', 'DomainManager', 'DomainManagerOperations', 
			'DoubleHolder', 'DoubleSeqHelper', 'DoubleSeqHolder', 
			'DynAny', 'DynArray', 'DynEnum', 
			'DynFixed', 'DynSequence', 'DynStruct', 
			'DynUnion', 'DynValue', 'DynamicImplementation', 
			'Environment', 'ExceptionList', 'FREE_MEM', 
			'FieldNameHelper', 'FixedHolder', 'FloatHolder', 
			'FloatSeqHelper', 'FloatSeqHolder', 'IDLType', 
			'IDLTypeHelper', 'IDLTypeOperations', 'IMP_LIMIT', 
			'INITIALIZE', 'INTERNAL', 'INTF_REPOS', 
			'INVALID_ACTIVITY', 'INVALID_TRANSACTION', 'INV_FLAG', 
			'INV_IDENT', 'INV_OBJREF', 'INV_POLICY', 
			'IRObject', 'IRObjectOperations', 'IdentifierHelper', 
			'IntHolder', 'LocalObject', 'LongHolder', 
			'LongLongSeqHelper', 'LongLongSeqHolder', 'LongSeqHelper', 
			'LongSeqHolder', 'MARSHAL', 'NO_IMPLEMENT', 
			'NO_MEMORY', 'NO_PERMISSION', 'NO_RESOURCES', 
			'NO_RESPONSE', 'NVList', 'NameValuePair', 
			'NameValuePairHelper', 'NamedValue', 'OBJECT_NOT_EXIST', 
			'OBJ_ADAPTER', 'OMGVMCID', 'ORB', 
			'Object', 'ObjectHelper', 'ObjectHolder', 
			'OctetSeqHelper', 'OctetSeqHolder', 'PERSIST_STORE', 
			'PRIVATE_MEMBER', 'PUBLIC_MEMBER', 'ParameterMode', 
			'ParameterModeHelper', 'ParameterModeHolder', 'Policy', 
			'PolicyError', 'PolicyErrorCodeHelper', 'PolicyErrorHelper', 
			'PolicyErrorHolder', 'PolicyHelper', 'PolicyHolder', 
			'PolicyListHelper', 'PolicyListHolder', 'PolicyOperations', 
			'PolicyTypeHelper', 'Principal', 'PrincipalHolder', 
			'REBIND', 'RepositoryIdHelper', 'Request', 
			'ServerRequest', 'ServiceDetail', 'ServiceDetailHelper', 
			'ServiceInformation', 'ServiceInformationHelper', 'ServiceInformationHolder', 
			'SetOverrideType', 'SetOverrideTypeHelper', 'ShortHolder', 
			'ShortSeqHelper', 'ShortSeqHolder', 'StringHolder', 
			'StringSeqHelper', 'StringSeqHolder', 'StringValueHelper', 
			'StructMember', 'StructMemberHelper', 'SystemException', 
			'TCKind', 'TIMEOUT', 'TRANSACTION_MODE', 
			'TRANSACTION_REQUIRED', 'TRANSACTION_ROLLEDBACK', 'TRANSACTION_UNAVAILABLE', 
			'TRANSIENT', 'TypeCode', 'TypeCodeHolder', 
			'ULongLongSeqHelper', 'ULongLongSeqHolder', 'ULongSeqHelper', 
			'ULongSeqHolder', 'UNKNOWN', 'UNSUPPORTED_POLICY', 
			'UNSUPPORTED_POLICY_VALUE', 'UShortSeqHelper', 'UShortSeqHolder', 
			'UnionMember', 'UnionMemberHelper', 'UnknownUserException', 
			'UnknownUserExceptionHelper', 'UnknownUserExceptionHolder', 'UserException', 
			'VM_ABSTRACT', 'VM_CUSTOM', 'VM_NONE', 
			'VM_TRUNCATABLE', 'ValueBaseHelper', 'ValueBaseHolder', 
			'ValueMember', 'ValueMemberHelper', 'VersionSpecHelper', 
			'VisibilityHelper', 'WCharSeqHelper', 'WCharSeqHolder', 
			'WStringSeqHelper', 'WStringSeqHolder', 'WStringValueHelper', 
			'WrongTransaction', 'WrongTransactionHelper', 'WrongTransactionHolder', 
			'_IDLTypeStub', '_PolicyStub'),

		'java/java/org/omg/CORBA',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA/{FNAME}.html'
	);


	//org.omg.CORBA_2_3   
	$context->addKeywordGroup(array(
			'ORB'),

		'java/java/org/omg/CORBA_2_3',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA_2_3/{FNAME}.html'
	);


	//org.omg.CORBA_2_3.portable   
	$context->addKeywordGroup(array(
			'Delegate', 'InputStream', 'ObjectImpl', 
			'OutputStream'),

		'java/java/org/omg/CORBA_2_3/portable',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA_2_3/portable/{FNAME}.html'
	);


	//org.omg.CORBA.DynAnyPackage
	$context->addKeywordGroup(array(
			'Invalid', 'InvalidSeq', 'InvalidValue', 
			'TypeMismatch'),

		'java/java/org/omg/CORBA/DynAnyPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA/DynAnyPackage/{FNAME}.html'
	);


	//org.omg.CORBA.ORBPackage
	$context->addKeywordGroup(array(
			'InconsistentTypeCode', 'InvalidName'),

		'java/java/org/omg/CORBA/ORBPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA/ORBPackage/{FNAME}.html'
	);


	//org.omg.CORBA.portable   
	$context->addKeywordGroup(array(
			'ApplicationException', 'BoxedValueHelper', 'CustomValue', 
			'Delegate', 'IDLEntity', 'IndirectionException', 
			'InputStream', 'InvokeHandler', 'ObjectImpl', 
			'OutputStream', 'RemarshalException', 'ResponseHandler', 
			'ServantObject', 'Streamable', 'StreamableValue', 
			'UnknownException', 'ValueBase', 'ValueFactory', 
			'ValueInputStream', 'ValueOutputStream'),

		'java/java/org/omg/CORBA/portable',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA/portable/{FNAME}.html'
	);


	//org.omg.CORBA.TypeCodePackage
	$context->addKeywordGroup(array(
			'BadKind', 'Bounds'),

		'java/java/org/omg/CORBA/TypeCodePackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CORBA/TypeCodePackage/{FNAME}.html'
	);


	//org.omg.CosNaming  
	$context->addKeywordGroup(array(
			'Binding', 'BindingHelper', 'BindingHolder', 
			'BindingIterator', 'BindingIteratorHelper', 'BindingIteratorHolder', 
			'BindingIteratorOperations', 'BindingIteratorPOA', 'BindingListHelper', 
			'BindingListHolder', 'BindingType', 'BindingTypeHelper', 
			'BindingTypeHolder', 'IstringHelper', 'NameComponent', 
			'NameComponentHelper', 'NameComponentHolder', 'NameHelper', 
			'NameHolder', 'NamingContext', 'NamingContextExt', 
			'NamingContextExtHelper', 'NamingContextExtHolder', 'NamingContextExtOperations', 
			'NamingContextExtPOA', 'NamingContextHelper', 'NamingContextHolder', 
			'NamingContextOperations', 'NamingContextPOA', '_BindingIteratorImplBase', 
			'_BindingIteratorStub', '_NamingContextExtStub', '_NamingContextImplBase', 
			'_NamingContextStub'),

		'java/java/org/omg/CosNaming',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CosNaming/{FNAME}.html'
	);


	//org.omg.CosNaming.NamingContextExtPackage 
	$context->addKeywordGroup(array(
			'AddressHelper', 'InvalidAddress', 'InvalidAddressHelper', 
			'InvalidAddressHolder', 'StringNameHelper', 'URLStringHelper'),

		'java/java/org/omg/CosNaming/NamingContextExtPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CosNaming/NamingContextExtPackage/{FNAME}.html'
	);


	//org.omg.CosNaming.NamingContextPackage  
	$context->addKeywordGroup(array(
			'AlreadyBound', 'AlreadyBoundHelper', 'AlreadyBoundHolder', 
			'CannotProceed', 'CannotProceedHelper', 'CannotProceedHolder', 
			'InvalidName', 'InvalidNameHelper', 'InvalidNameHolder', 
			'NotEmpty', 'NotEmptyHelper', 'NotEmptyHolder', 
			'NotFound', 'NotFoundHelper', 'NotFoundHolder', 
			'NotFoundReason', 'NotFoundReasonHelper', 'NotFoundReasonHolder'),

		'java/java/org/omg/CosNaming/NamingContextPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/CosNaming/NamingContextPackage/{FNAME}.html'
	);


	//org.omg.Dynamic 
	$context->addKeywordGroup(array(
			'Parameter'),

		'java/java/org/omg/Dynamic',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/Dynamic/{FNAME}.html'
	);


	//org.omg.DynamicAny   
	$context->addKeywordGroup(array(
			'AnySeqHelper', 'DynAny', 'DynAnyFactory', 
			'DynAnyFactoryHelper', 'DynAnyFactoryOperations', 'DynAnyHelper', 
			'DynAnyOperations', 'DynAnySeqHelper', 'DynArray', 
			'DynArrayHelper', 'DynArrayOperations', 'DynEnum', 
			'DynEnumHelper', 'DynEnumOperations', 'DynFixed', 
			'DynFixedHelper', 'DynFixedOperations', 'DynSequence', 
			'DynSequenceHelper', 'DynSequenceOperations', 'DynStruct', 
			'DynStructHelper', 'DynStructOperations', 'DynUnion', 
			'DynUnionHelper', 'DynUnionOperations', 'DynValue', 
			'DynValueBox', 'DynValueBoxOperations', 'DynValueCommon', 
			'DynValueCommonOperations', 'DynValueHelper', 'DynValueOperations', 
			'FieldNameHelper', 'NameDynAnyPair', 'NameDynAnyPairHelper', 
			'NameDynAnyPairSeqHelper', 'NameValuePair', 'NameValuePairHelper', 
			'NameValuePairSeqHelper', '_DynAnyFactoryStub', '_DynAnyStub', 
			'_DynArrayStub', '_DynEnumStub', '_DynFixedStub', 
			'_DynSequenceStub', '_DynStructStub', '_DynUnionStub', 
			'_DynValueStub'),

		'java/java/org/omg/DynamicAny',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/DynamicAny/{FNAME}.html'
	);


	//org.omg.DynamicAny.DynAnyFactoryPackage 
	$context->addKeywordGroup(array(
			'InconsistentTypeCode', 'InconsistentTypeCodeHelper'),

		'java/java/org/omg/DynamicAny/DynAnyFactoryPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/DynamicAny/DynAnyFactoryPackage/{FNAME}.html'
	);


	//org.omg.DynamicAny.DynAnyPackage   
	$context->addKeywordGroup(array(
			'InvalidValue', 'InvalidValueHelper', 'TypeMismatch', 
			'TypeMismatchHelper'),

		'java/java/org/omg/DynamicAny/DynAnyPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/DynamicAny/DynAnyPackage/{FNAME}.html'
	);


	//org.omg.IOP  
	$context->addKeywordGroup(array(
			'CodeSets', 'Codec', 'CodecFactory', 
			'CodecFactoryHelper', 'CodecFactoryOperations', 'CodecOperations', 
			'ComponentIdHelper', 'ENCODING_CDR_ENCAPS', 'Encoding', 
			'ExceptionDetailMessage', 'IOR', 'IORHelper', 
			'IORHolder', 'MultipleComponentProfileHelper', 'MultipleComponentProfileHolder', 
			'ProfileIdHelper', 'RMICustomMaxStreamFormat', 'ServiceContext', 
			'ServiceContextHelper', 'ServiceContextHolder', 'ServiceContextListHelper', 
			'ServiceContextListHolder', 'ServiceIdHelper', 'TAG_ALTERNATE_IIOP_ADDRESS', 
			'TAG_CODE_SETS', 'TAG_INTERNET_IOP', 'TAG_JAVA_CODEBASE', 
			'TAG_MULTIPLE_COMPONENTS', 'TAG_ORB_TYPE', 'TAG_POLICIES', 
			'TAG_RMI_CUSTOM_MAX_STREAM_FORMAT', 'TaggedComponent', 'TaggedComponentHelper', 
			'TaggedComponentHolder', 'TaggedProfile', 'TaggedProfileHelper', 
			'TaggedProfileHolder', 'TransactionService'),

		'java/java/org/omg/IOP',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/IOP/{FNAME}.html'
	);


	//org.omg.IOP.CodecFactoryPackage   
	$context->addKeywordGroup(array(
			'UnknownEncoding', 'UnknownEncodingHelper'),

		'java/java/org/omg/IOP/CodecFactoryPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/IOP/CodecFactoryPackage/{FNAME}.html'
	);


	//org.omg.IOP.CodecPackage 
	$context->addKeywordGroup(array(
			'FormatMismatch', 'FormatMismatchHelper', 'InvalidTypeForEncoding', 
			'InvalidTypeForEncodingHelper', 'TypeMismatch', 'TypeMismatchHelper'),

		'java/java/org/omg/IOP/CodecPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/IOP/CodecPackage/{FNAME}.html'
	);


	//org.omg.Messaging
	$context->addKeywordGroup(array(
			'SYNC_WITH_TRANSPORT', 'SyncScopeHelper'),

		'java/java/org/omg/Messaging',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/Messaging/{FNAME}.html'
	);


	//org.omg.PortableInterceptor   
	$context->addKeywordGroup(array(
			'ACTIVE', 'AdapterManagerIdHelper', 'AdapterNameHelper', 
			'AdapterStateHelper', 'ClientRequestInfo', 'ClientRequestInfoOperations', 
			'ClientRequestInterceptor', 'ClientRequestInterceptorOperations', 'Current', 
			'CurrentHelper', 'CurrentOperations', 'DISCARDING', 
			'ForwardRequest', 'ForwardRequestHelper', 'HOLDING', 
			'INACTIVE', 'IORInfo', 'IORInfoOperations', 
			'IORInterceptor', 'IORInterceptorOperations', 'IORInterceptor_3_0', 
			'IORInterceptor_3_0Helper', 'IORInterceptor_3_0Holder', 'IORInterceptor_3_0Operations', 
			'Interceptor', 'InterceptorOperations', 'InvalidSlot', 
			'InvalidSlotHelper', 'LOCATION_FORWARD', 'NON_EXISTENT', 
			'ORBIdHelper', 'ORBInitInfo', 'ORBInitInfoOperations', 
			'ORBInitializer', 'ORBInitializerOperations', 'ObjectIdHelper', 
			'ObjectReferenceFactory', 'ObjectReferenceFactoryHelper', 'ObjectReferenceFactoryHolder', 
			'ObjectReferenceTemplate', 'ObjectReferenceTemplateHelper', 'ObjectReferenceTemplateHolder', 
			'ObjectReferenceTemplateSeqHelper', 'ObjectReferenceTemplateSeqHolder', 'PolicyFactory', 
			'PolicyFactoryOperations', 'RequestInfo', 'RequestInfoOperations', 
			'SUCCESSFUL', 'SYSTEM_EXCEPTION', 'ServerIdHelper', 
			'ServerRequestInfo', 'ServerRequestInfoOperations', 'ServerRequestInterceptor', 
			'ServerRequestInterceptorOperations', 'TRANSPORT_RETRY', 'UNKNOWN', 
			'USER_EXCEPTION'),

		'java/java/org/omg/PortableInterceptor',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableInterceptor/{FNAME}.html'
	);


	//org.omg.PortableInterceptor.ORBInitInfoPackage   
	$context->addKeywordGroup(array(
			'DuplicateName', 'DuplicateNameHelper', 'InvalidName', 
			'InvalidNameHelper', 'ObjectIdHelper'),

		'java/java/org/omg/PortableInterceptor/ORBInitInfoPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableInterceptor/ORBInitInfoPackage/{FNAME}.html'
	);


	//org.omg.PortableServer  
	$context->addKeywordGroup(array(
			'AdapterActivator', 'AdapterActivatorOperations', 'Current', 
			'CurrentHelper', 'CurrentOperations', 'DynamicImplementation', 
			'ForwardRequest', 'ForwardRequestHelper', 'ID_ASSIGNMENT_POLICY_ID', 
			'ID_UNIQUENESS_POLICY_ID', 'IMPLICIT_ACTIVATION_POLICY_ID', 'IdAssignmentPolicy', 
			'IdAssignmentPolicyOperations', 'IdAssignmentPolicyValue', 'IdUniquenessPolicy', 
			'IdUniquenessPolicyOperations', 'IdUniquenessPolicyValue', 'ImplicitActivationPolicy', 
			'ImplicitActivationPolicyOperations', 'ImplicitActivationPolicyValue', 'LIFESPAN_POLICY_ID', 
			'LifespanPolicy', 'LifespanPolicyOperations', 'LifespanPolicyValue', 
			'POA', 'POAHelper', 'POAManager', 
			'POAManagerOperations', 'POAOperations', 'REQUEST_PROCESSING_POLICY_ID', 
			'RequestProcessingPolicy', 'RequestProcessingPolicyOperations', 'RequestProcessingPolicyValue', 
			'SERVANT_RETENTION_POLICY_ID', 'Servant', 'ServantActivator', 
			'ServantActivatorHelper', 'ServantActivatorOperations', 'ServantActivatorPOA', 
			'ServantLocator', 'ServantLocatorHelper', 'ServantLocatorOperations', 
			'ServantLocatorPOA', 'ServantManager', 'ServantManagerOperations', 
			'ServantRetentionPolicy', 'ServantRetentionPolicyOperations', 'ServantRetentionPolicyValue', 
			'THREAD_POLICY_ID', 'ThreadPolicy', 'ThreadPolicyOperations', 
			'ThreadPolicyValue', '_ServantActivatorStub', '_ServantLocatorStub'),

		'java/java/org/omg/PortableServer',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/{FNAME}.html'
	);


	//org.omg.PortableServer.CurrentPackage
	$context->addKeywordGroup(array(
			'NoContext', 'NoContextHelper'),

		'java/java/org/omg/PortableServer/CurrentPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/CurrentPackage/{FNAME}.html'
	);


	//org.omg.PortableServer.POAManagerPackage 
	$context->addKeywordGroup(array(
			'AdapterInactive', 'AdapterInactiveHelper', 'State'),

		'java/java/org/omg/PortableServer/POAManagerPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/POAManagerPackage/{FNAME}.html'
	);


	//org.omg.PortableServer.POAPackage   
	$context->addKeywordGroup(array(
			'AdapterAlreadyExists', 'AdapterAlreadyExistsHelper', 'AdapterNonExistent', 
			'AdapterNonExistentHelper', 'InvalidPolicy', 'InvalidPolicyHelper', 
			'NoServant', 'NoServantHelper', 'ObjectAlreadyActive', 
			'ObjectAlreadyActiveHelper', 'ObjectNotActive', 'ObjectNotActiveHelper', 
			'ServantAlreadyActive', 'ServantAlreadyActiveHelper', 'ServantNotActive', 
			'ServantNotActiveHelper', 'WrongAdapter', 'WrongAdapterHelper', 
			'WrongPolicy', 'WrongPolicyHelper'),

		'java/java/org/omg/PortableServer/POAPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/POAPackage/{FNAME}.html'
	);


	//org.omg.PortableServer.portable   
	$context->addKeywordGroup(array(
			'Delegate'),

		'java/java/org/omg/PortableServer/portable',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/portable/{FNAME}.html'
	);


	//org.omg.PortableServer.ServantLocatorPackage
	$context->addKeywordGroup(array(
			'CookieHolder'),

		'java/java/org/omg/PortableServer/ServantLocatorPackage',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/PortableServer/ServantLocatorPackage/{FNAME}.html'
	);


	//org.omg.SendingContext   
	$context->addKeywordGroup(array(
			'RunTime', 'RunTimeOperations'),

		'java/java/org/omg/SendingContext',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/SendingContext/{FNAME}.html'
	);


	//org.omg.stub.java.rmi   
	$context->addKeywordGroup(array(
			'_Remote_Stub'),

		'java/java/org/omg/stub/java/rmi',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/omg/stub/java/rmi/{FNAME}.html'
	);


	//org.w3c.dom   
	$context->addKeywordGroup(array(
			'Attr', 'CDATASection', 'CharacterData', 
			'Comment', 'DOMConfiguration', 'DOMError', 
			'DOMErrorHandler', 'DOMException', 'DOMImplementation', 
			'DOMImplementationList', 'DOMImplementationSource', 'DOMLocator', 
			'DOMStringList', 'Document', 'DocumentFragment', 
			'DocumentType', 'Element', 'Entity', 
			'EntityReference', 'NameList', 'NamedNodeMap', 
			'Node', 'NodeList', 'Notation', 
			'ProcessingInstruction', 'Text', 'TypeInfo', 
			'UserDataHandler'),

		'java/java/org/w3c/dom',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/w3c/dom/{FNAME}.html'
	);


	//org.w3c.dom.bootstrap   
	$context->addKeywordGroup(array(
			'DOMImplementationRegistry'),

		'java/java/org/w3c/dom/bootstrap',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/w3c/dom/bootstrap/{FNAME}.html'
	);


	//org.w3c.dom.events   
	$context->addKeywordGroup(array(
			'DocumentEvent', 'Event', 'EventException', 
			'EventListener', 'EventTarget', 'MouseEvent', 
			'MutationEvent', 'UIEvent'),

		'java/java/org/w3c/dom/events',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/w3c/dom/events/{FNAME}.html'
	);


	//org.w3c.dom.ls   
	$context->addKeywordGroup(array(
			'DOMImplementationLS', 'LSException', 'LSInput', 
			'LSLoadEvent', 'LSOutput', 'LSParser', 
			'LSParserFilter', 'LSProgressEvent', 'LSResourceResolver', 
			'LSSerializer', 'LSSerializerFilter'),

		'java/java/org/w3c/dom/ls',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/w3c/dom/ls/{FNAME}.html'
	);


	//org.xml.sax   
	$context->addKeywordGroup(array(
			'AttributeList', 'Attributes', 'ContentHandler', 
			'DTDHandler', 'DocumentHandler', 'EntityResolver', 
			'ErrorHandler', 'HandlerBase', 'InputSource', 
			'Locator', 'Parser', 'SAXException', 
			'SAXNotRecognizedException', 'SAXNotSupportedException', 'SAXParseException', 
			'XMLFilter', 'XMLReader'),

		'java/java/org/xml/sax',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/xml/sax/{FNAME}.html'
	);


	//org.xml.sax.ext
	$context->addKeywordGroup(array(
			'Attributes2', 'Attributes2Impl', 'DeclHandler', 
			'DefaultHandler2', 'EntityResolver2', 'LexicalHandler', 
			'Locator2', 'Locator2Impl'),

		'java/java/org/xml/sax/ext',
		true,
		'http://java.sun.com/j2se/1.5.0/docs/api/org/xml/sax/ext/{FNAME}.html'
	);
    
    // Constants
    $context->addKeywordGroup(array(
            'false', 'null', 'true'
    ), 'const', true);
    
    $context->setCharactersDisallowedBeforeKeywords("'");
    $context->setCharactersDisallowedAfterKeywords("'");
    
    // Symbols
    $context->addSymbolGroup(array(
        '(', ')', ',', ';', ':', '[', ']',
        '+', '-', '*', '%', '/', '&', '|', '!', '?', 
        '<', '>', '{', '}', '=', '.', '@'
    ), 'symbol');

    // Numbers
    $context->useStandardIntegers();
    $context->useStandardDoubles();

    // Objects
    $context->addObjectSplitter('.', 'ootoken', 'symbol');

    $context->setComplexFlag(GESHI_COMPLEX_TOKENISE);    
}

function geshi_java_java_single_string (&$context)
{
    $context->addDelimiters("'", "'");
    // @todo [blocking 1.1.2] need to think whether this is necessary for
    // single char context
    //$context->addEscapeGroup('\\', "'");
    $context->setEscapeCharacters('\\');
    $context->setCharactersToEscape(array('\\', "'"));
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    //$context->_contextStyleType = GESHI_STYLE_STRINGS;
}

function geshi_java_java_double_string (&$context)
{
    $context->addDelimiters('"', array('"', "\n"));
    //$context->setEscapeCharacters('\\');
    //$context->setCharactersToEscape(array('n', 'r', 't', '\\', '"', "\n"));
    $context->addEscapeGroup('\\', array('n', 'r', 't'/*, '"'*/, "\n"));
    // @todo may be able to do this a better way (not using constants), and not so many calls?
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    // @todo dunno about this one yet    
    //$context->_contextStyleType = GESHI_STYLE_STRINGS;
}

function geshi_java_java_single_comment (&$context)
{
    $context->addDelimiters('//', "\n");
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

function geshi_java_java_multi_comment (&$context)
{
    $context->addDelimiters('/*', '*/');
    $context->setComplexFlag(GESHI_COMPLEX_PASSALL);
    //$this->_contextStyleType = GESHI_STYLE_COMMENTS;
}

/**#@-*/

?>

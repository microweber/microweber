var Endian = {BIG : 0,LITTLE : 1};

var ZipConstants = {
    LOCSIG: 0x04034b50,	// "PK\003\004"
    LOCHDR: 30,	// LOC header size
    LOCVER: 4,	// version needed to extract
    LOCNAM: 26, // filename length
    EXTSIG: 0x08074b50,	// "PK\007\008"
    EXTHDR: 16,	// EXT header size
    CENSIG: 0x02014b50,	// "PK\001\002"
    CENHDR: 46,	// CEN header size
    CENVER: 6, // version needed to extract
    CENNAM: 28, // filename length
    CENOFF: 42, // LOC header offset
    ENDSIG: 0x06054b50,	// "PK\005\006"
    ENDHDR: 22, // END header size
    ENDTOT: 10,	// total number of entries
    ENDOFF: 16, // offset of first CEN header
    STORED: 0,
    DEFLATED: 8
};

var Base64 = function(input) {
    var StringMaker = undefined;
    if(navigator.userAgent.toLowerCase().indexOf(" chrome/")>=0||navigator.userAgent.toLowerCase().indexOf(" firefox/")>=0||
    navigator.userAgent.toLowerCase().indexOf(' gecko/')>=0){StringMaker=function(){this.str="";this.length=0;
    this.append=function(s){this.str+=s;this.length+=s.length;};this.prepend=function(s){this.str=s+this.str;this.length+=s.length;};
    this.toString=function(){return this.str;}}}else{StringMaker=function(){this.parts=[];this.length=0;this.append=function(s){
    this.parts.push(s);this.length+=s.length;};this.prepend=function(s){this.parts.unshift(s);this.length+=s.length;};
    this.toString=function(){return this.parts.join('');}}}
    var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o=new StringMaker(),a,b,c,d,f,g,h,i=0;while(i<input.length){a=input.charCodeAt(i++);
    b=input.charCodeAt(i++);c=input.charCodeAt(i++);d=a>>2;f=((a&3)<<4)|(b>>4);g=((b&15)<<2)|(c>>6);h=c&63;
    if(isNaN(b)){g=h=64;}else if(isNaN(c)){h=64;}o.append(keyStr.charAt(d)+keyStr.charAt(f)+keyStr.charAt(g)
    +keyStr.charAt(h));}return o.toString();
}

var BA = function(byteData, endianType) {
    var _bytes = '', _len = 0, _pos = 0,_endian = 0;
    if (byteData) {
        _bytes = byteData || '';
        _endian = endianType !== undefined ? endianType : _endian;
        _len = byteData.length;
    }

    var isBA = typeof byteData != 'string' && byteData !== undefined;
    var warn = function(msg){
        throw msg;
    };
    return {
        position : function(val) { if (val) _pos = val; else return _pos; },
        move : function(val) { _pos += val},
        bytesAvailable : function() { return _len - _pos; },
        length : function() { return _len; },
        endian : function(val) { if (val) _endian = val; else return _endian; },
        data : function(val) { if (val) { _bytes = val || ''; _len = _bytes.length; isBA = typeof val != 'string' && val !== undefined; } else return _bytes; },
        readByte : function() {
            if (this.bytesAvailable() === 0) { warn("readByte::End of stream!"); }
            return isBA ? _bytes[_pos++] & 0xFF : (_bytes.charCodeAt(_pos++) & 0xFF);
        },
        readByteAt : function(index) {
          if (index < _len) {
              return isBA ? _bytes[index] & 0xFF :_bytes.charCodeAt(index) & 0xFF
          }
          return warn("readByteAt::End of stream");
        },
        writeByte : function(val) {
            if (isBA) {
                if (_pos < _len) {
                    _bytes[_pos] = val & 0xFF;
                } else {
                    _bytes[_bytes.length++] = val;
                }
                _pos++;
                return;
            }
            if (_pos < _len) {
                _bytes = _bytes.substr(0, _pos) + String.fromCharCode(val & 0xFF) + _bytes.substring(_pos + 1);
            } else {
                _bytes += String.fromCharCode(val & 0xFF);
                _len += 1;
            }
            _pos++;
        },
        readBytes : function(offset, length) {
            if (length === undefined) {
                var p = _pos;
                _pos += offset;
                if (isBA) {
                    var tmp = '';
                    for (var i = p; i < p + offset; i++) {
                        tmp += String.fromCharCode(_bytes[i]);
                    }
                    return tmp;
                } else {
                    return _bytes.substr(p, offset);
                }
            }
            if (isBA) {
                var tmpx = '';
                for (var j = offset; j < offset + length; j++) {
                    tmpx += String.fromCharCode(_bytes[j]);
                }
                return tmpx;
            }
            return _bytes.substr(offset, length);
        },
        readUnsignedInt : function() {
            if (this.bytesAvailable() < 4) { throw "End of stream!"; }
            var p = 0, x = 0;
            if (_endian == Endian.BIG) {
                 p = (_pos += 4) - 4;
                if (isBA) {
                    x = ((_bytes[p] & 0xFF) << 24) | ((_bytes[++p] & 0xFF) << 16) | ((_bytes[++p] & 0xFF) << 8) | (_bytes[++p] & 0xFF);
                } else
                x =  ((_bytes.charCodeAt(p) & 0xFF) << 24) | ((_bytes.charCodeAt(++p) & 0xFF) << 16) | ((_bytes.charCodeAt(++p) & 0xFF) << 8) | (_bytes.charCodeAt(++p) & 0xFF);
            } else {
                p = (_pos += 4);
                if (isBA) {
                   x =  ((_bytes[--p] & 0xFF) << 24) | ((_bytes[--p] & 0xFF) << 16) | ((_bytes[--p] & 0xFF) << 8) | (_bytes[--p] & 0xFF);
                } else
                x =  ((_bytes.charCodeAt(--p) & 0xFF) << 24) | ((_bytes.charCodeAt(--p) & 0xFF) << 16) | ((_bytes.charCodeAt(--p) & 0xFF) << 8) | (_bytes.charCodeAt(--p) & 0xFF);
            }
            return x;
        },
        readUnsignedShort : function() {
            if (this.bytesAvailable() < 2) { throw "End of stream!"; }
            var p = 0;
            if (_endian == Endian.BIG) {
                p = (_pos += 2) - 2;
                if (isBA) {
                    return ((_bytes[p] & 0xFF) << 8) | (_bytes[++p] & 0xFF);
                } else
                return ((_bytes.charCodeAt(p) & 0xFF) << 8) | (_bytes.charCodeAt(++p) & 0xFF);
            } else {
                p = (_pos += 2);
                if (isBA) {
                    return ((_bytes[--p] & 0xFF) << 8) | (_bytes[--p] & 0xFF);
                } else
                return ((_bytes.charCodeAt(--p) & 0xFF) << 8) | (_bytes.charCodeAt(--p) & 0xFF);
            }
        },
        readShort : function() {
            if (this.bytesAvailable() < 2) { throw "End of stream!"; }
            var p = 0, x = 0;
            if (_endian == Endian.BIG) {
                p = (_pos += 2) - 2;
                if (isBA) {
                    x = ((_bytes[p] & 0xFF) << 8) | (_bytes[++p] & 0xFF);
                } else
                x = ((_bytes.charCodeAt(p) & 0xFF) << 8) | (_bytes.charCodeAt(++p) & 0xFF);
            } else {
                p = (_pos += 2);
                if (isBA) {
                    x = ((_bytes[--p] & 0xFF) << 8) | (_bytes[--p] & 0xFF);
                } else
                x = ((_bytes.charCodeAt(--p) & 0xFF) << 8) | (_bytes.charCodeAt(--p) & 0xFF);
            }
            return (x >= 32768) ? x - 65536 : x;
        },
        readUTFBytes : function(readLength) {
            readLength = readLength || 0;
            var output = '';
            for (var i = 0; i < readLength; i++) {
                output += String.fromCharCode(this.readByte());
            }
            return output;
        }
    }
};

var Inflater = function() {
    var MAXBITS = 15, MAXLCODES = 286, MAXDCODES = 30, MAXCODES = 316, FIXLCODES = 288,
        LENS = [3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15, 17, 19, 23, 27, 31, 35, 43, 51, 59, 67, 83, 99, 115, 131, 163, 195, 227, 258],
        LEXT = [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 0],
        DISTS = [1, 2, 3, 4, 5, 7, 9, 13, 17, 25, 33, 49, 65, 97, 129, 193, 257, 385, 513, 769, 1025, 1537, 2049, 3073, 4097, 6145, 8193, 12289, 16385, 24577],
        DEXT = [ 0, 0, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 9, 10, 10, 11, 11, 12, 12, 13, 13],
        inbuf = undefined, // input buffer - ByteArray
        incnt = 0,  // bytes read so far
        bitbuf = 0, // bit buffer
        bitcnt = 0, // number of bits in bit buffer
        // Huffman code decoding tables
        lencode = undefined,
        distcode = undefined;

    function bits(need) {
        var val = bitbuf;
        while(bitcnt < need) {
            if (incnt == inbuf.length()) throw 'available inflate data did not terminate';
            val |= inbuf.readByteAt(incnt++) << bitcnt;
            bitcnt += 8;
        }
        bitbuf = val >> need;
        bitcnt -= need;
        return val & ((1 << need) - 1);
    }

    function construct(h, length, n) {
        var offs = new Array();
        for (var len = 0; len <= MAXBITS; len++) h.count[len] = 0;
        for (var symbol = 0; symbol < n; symbol++) h.count[length[symbol]]++;
        if(h.count[0] == n) return 0;
        var left = 1;
        for(len = 1; len <= MAXBITS; len++) {
            left <<= 1;
            left -= h.count[len];
            if(left < 0) return left;
        }
        offs[1] = 0;
        for(len = 1; len < MAXBITS; len++) offs[len + 1] = offs[len] + h.count[len];
        for(symbol = 0; symbol < n; symbol++)
            if(length[symbol] !== 0) h.symbol[offs[length[symbol]]++] = symbol;
        return left;
    }

    function decode(h) {
        var code = 0, first = 0, index = 0;
        for(var len = 1; len <= MAXBITS; len++) {
            code |= bits(1);
            var count = h.count[len];
            if(code < first + count) return h.symbol[index + (code - first)];
            index += count;
            first += count;
            first <<= 1;
            code <<= 1;
        }
        return -9; // ran out of codes
    }

    function codes(buf) {
        do {
            var symbol = decode(lencode);
            if(symbol < 0) return symbol;
            if(symbol < 256) {
                buf.position(buf.length());
                buf.writeByte(symbol);
            }
            else if(symbol > 256) {
                symbol -= 257;
                if(symbol >= 29) throw "invalid literal/length or distance code in fixed or dynamic block";
                var len = LENS[symbol] + bits(LEXT[symbol]);
                symbol = decode(distcode);
                if(symbol < 0) return symbol;
                var dist = DISTS[symbol] + bits(DEXT[symbol]);
                if(dist > buf.length()) throw "distance is too far back in fixed or dynamic block";
                buf.position(buf.length());
                while(len--) buf.writeByte(buf.readByteAt(buf.length() - dist));
            }
        } while (symbol != 256);
        return 0;
    }

    function stored(buf) {
        bitbuf = 0;
        bitcnt = 0;
        if(incnt + 4 > inbuf.length()) throw 'available inflate data did not terminate';
        var len = inbuf[incnt++];
        len |= inbuf[incnt++] << 8;
        if(inbuf[incnt++] != (~len & 0xff) || inbuf[incnt++] != ((~len >> 8) & 0xff))
            throw "stored block length did not match one's complement";
        if(incnt + len > inbuf.length()) throw 'available inflate data did not terminate';
        while(len--) buf[buf.length] = inbuf[incnt++];
    }

    function constructFixedTables() {
        var lengths = new Array();
        // literal/length table
        for(var symbol = 0; symbol < 144; symbol++) lengths[symbol] = 8;
        for(; symbol < 256; symbol++) lengths[symbol] = 9;
        for(; symbol < 280; symbol++) lengths[symbol] = 7;
        for(; symbol < FIXLCODES; symbol++) lengths[symbol] = 8;
        construct(lencode, lengths, FIXLCODES);
        for(symbol = 0; symbol < MAXDCODES; symbol++) lengths[symbol] = 5;
        construct(distcode, lengths, MAXDCODES);
    }

    function constructDynamicTables() {
        var lengths = new Array(),
            order = [16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15],
            nlen = bits(5) + 257,
            ndist = bits(5) + 1,
            ncode = bits(4) + 4;
        if(nlen > MAXLCODES || ndist > MAXDCODES) throw "dynamic block code description: too many length or distance codes";
        for(var index = 0; index < ncode; index++) lengths[order[index]] = bits(3);
        for(; index < 19; index++) lengths[order[index]] = 0;
        var err = construct(lencode, lengths, 19);
        if(err !== 0) throw "dynamic block code description: code lengths codes incomplete";
        index = 0;
        while(index < nlen + ndist) {
            var symbol = decode(lencode), len;
            if(symbol < 16) lengths[index++] = symbol;
            else {
                len = 0;
                if(symbol == 16) {
                    if(index === 0) throw "dynamic block code description: repeat lengths with no first length";
                    len = lengths[index - 1];
                    symbol = 3 + bits(2);
                }
                else if(symbol == 17) symbol = 3 + bits(3);
                else symbol = 11 + bits(7);
                if(index + symbol > nlen + ndist)
                    throw "dynamic block code description: repeat more than specified lengths";
                while(symbol--) lengths[index++] = len;
            }
        }

        err = construct(lencode, lengths, nlen);
        if(err < 0 || (err > 0 && nlen - lencode.count[0] != 1)) throw "dynamic block code description: invalid literal/length code lengths";
        err = construct(distcode, lengths.slice(nlen), ndist);
        if(err < 0 || (err > 0 && ndist - distcode.count[0] != 1)) throw "dynamic block code description: invalid distance code lengths";
        return err;
    }

    return {
        setInput : function(buf) {
            inbuf = buf;
            inbuf.endian(Endian.LITTLE);
            inbuf.position(0);
        },
        inflate : function(buf) {
            incnt = bitbuf = bitcnt = 0;
            var err = 0;
            do {
                var last = bits(1);
                var type = bits(2);
                
                if(type === 0) stored(buf); // uncompressed block
                else if(type == 3) throw 'invalid block type (type == 3)';
                else { // compressed block
                    lencode = {count:new Array(0), symbol:new Array(0)};
                    distcode = {count:new Array(0), symbol:new Array(0)};
                    if(type == 1) constructFixedTables();
                    else if(type == 2) err = constructDynamicTables();
                    if(err !== 0) return err;
                    err = codes(buf);
                }
                if(err !== 0) break;
            } while (!last);
            return err;
        }
    }
};

var ZipEntry = function(name) {
    var _name = name, dostime = 0, flag = 0, version = 0, offset = 0;
    return {
        name : function() { return _name; },
        time : function(val) {
            var d;
            if (val) {
                d = new Date(time);
                dostime = (d.fullYear - 1980 & 0x7f) << 25 | (d.month + 1) << 21 | d.day << 16 | d.hours << 11 | d.minutes << 5 | d.seconds >> 1;
            } else {
                d = new Date(((dostime >> 25) & 0x7f) + 1980,((dostime >> 21) & 0x0f) - 1,(dostime >> 16) & 0x1f,(dostime >> 11) & 0x1f,(dostime >> 5) & 0x3f,(dostime & 0x1f) << 1);
                return d.getTime();
            }
        },
        size : 0,
        compressedSize : 0,
        crc : 0,
        method : 0,
        extra : undefined,
        comment : '',
        isDirectory : function() {
            return _name.charAt(_name.length - 1) == '/';
        }
    }
};

var ZipFile = function(data) {
     var buf = undefined, // ByteArray
        entryList = [], // Array
        entryTable = {}, // Dict
        locOffsetTable = {}; // Dict

     buf = new BA(data.data(), Endian.LITTLE);
     readEntries();

     function readEntries() {
            readEND();
            entryTable = {};
            locOffsetTable = {};
            // read cen entries
            for(var i = 0; i < entryList.length; i++) {
                var tmpbuf = new BA(buf.readBytes(ZipConstants.CENHDR), Endian.LITTLE);
                if(tmpbuf.readUnsignedInt() != ZipConstants.CENSIG)  // "PK\005\006"
                    throw "readEntries::Invalid CEN header (bad signature)";
                // handle filename
                tmpbuf.position(28);
                var len = tmpbuf.readUnsignedShort();
                if(len === 0) throw "missing entry name";
                var e = new ZipEntry(buf.readUTFBytes(len));
                // handle extra field
                len = tmpbuf.readUnsignedShort();
                e.extra = new BA();
                if(len > 0) e.extra.data(buf.readBytes(len));
                // handle file comment
                buf.move(tmpbuf.readUnsignedShort());
                // now get the remaining fields for the entry
                tmpbuf.position(6); // version needed to extract
                e.version = tmpbuf.readUnsignedShort();
                e.flag = tmpbuf.readUnsignedShort();
                if ((e.flag & 1) == 1) throw "readEntries::Encrypted ZIP entry not supported";
                e.method = tmpbuf.readUnsignedShort();
                e.dostime = tmpbuf.readUnsignedInt();
                e.crc = tmpbuf.readUnsignedInt();
                e.compressedSize = tmpbuf.readUnsignedInt();
                e.size = tmpbuf.readUnsignedInt();
                // add to entries and table
                entryList[i] = e;
                entryTable[e.name()] = e;
                // loc offset
                tmpbuf.position(42); // LOC HEADER
                locOffsetTable[e.name()] = tmpbuf.readUnsignedInt();
            }
        }

        function readEND() {
            var b = new BA();
            b.endian(Endian.LITTLE);
            b.data(buf.readBytes(findEND(), ZipConstants.ENDHDR));
            b.position(ZipConstants.ENDTOT); // total number of entries
            entryList = new Array(b.readUnsignedShort());
            b.position(ZipConstants.ENDOFF); // offset of first CEN header
            buf.position(b.readUnsignedInt());
        }

        function findEND() {
            var i = buf.length() - ZipConstants.ENDHDR; // END header size
            var n = Math.max(0, i - 0xffff); // 0xffff is max zip file comment length
            for(i; i >= n; i--) {
                buf.position(i);
                if(buf.readByte() != 0x50) continue; // quick check that the byte is 'P'
                buf.position(i);
                if(buf.readUnsignedInt() == 0x06054b50) { // "PK\005\006"
                    return i;
                }
            }
            throw "findEND::Invalid zip";
        }

    return {
        entries : function() { return entryList },
        size : function() { return entryList.length },
        getEntry : function(name) { return entryTable[name]; },
        getInput : function(entry) {
            buf.position(locOffsetTable[entry.name()] + 30 - 2);
            var len = buf.readShort();
            buf.move(entry.name().length + len);
            var b1 = new BA();
            if(entry.compressedSize > 0) {
                b1.data(buf.readBytes(entry.compressedSize));
            }
            switch(entry.method) {
                case 0: // STORED
                    return b1;
                    break;
                case 8: // DEFLATED
                    var b2 = new BA();
                    var inflater = new Inflater();
                    inflater.setInput(b1);
                    inflater.inflate(b2);
                    b2.position(0)
                    return b2;
                    break;
                default:
                    throw "zipEntry::getInput::Invalid compression method";
            }
        }
    }
};

var ZipLoader = function(zipURL) {
    
    var ZIP_CACHE = {},
        ZIP_FILE_REG = new RegExp('.*?\.zip$', 'i'),
        ZIP_ENTRY_REG = new RegExp('(.*?\.zip):\/\/(.*$)', 'i'),
        _zipUrl = zipURL,
        _entryUrl = '',
        _entryDataFormat = '',
        isCompleteProcessRequired = true,
        data = undefined;

    if (_zipUrl) {
        loadBinaryResource(_zipUrl);
    }

    function isIE() {
        return navigator.userAgent.match(/MSIE/) !== null
    }

    function getXMLHttpObj(){
        if(typeof(XMLHttpRequest)!='undefined') {
            return new XMLHttpRequest();
        }
        var axO = ['Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.4.0', 'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'], i;
        for (i = 0; i < axO.length; i++) {
            try{
                return new ActiveXObject(axO[i]);
            }catch(e){}
        }
        return null;
    }

    function loadBinaryResource(url, c) {

        var req = getXMLHttpObj();
        req.open('GET', url, false);

        if(!req.overrideMimeType) {
            var vbScript = '<scr' + 'ipt type="text/vbscript">\n'+
                      '<!-' + '-\n' +
                      'Function BinaryToArray(Binary)\n'+
                      '  Dim i\n'+
                      '  ReDim byteArray(LenB(Binary))\n'+
                      '  For i = 1 To LenB(Binary)\n'+
                      '    byteArray(i-1) = AscB(MidB(Binary, i, 1))\n'+
                      '  Next\n'+
                      '  BinaryToArray = byteArray\n'+
                      'End Function\n'+
                      '--' + '>\n' +
                      '</scr' + 'ipt>';
            document.write(vbScript);
            req.setRequestHeader('Accept-Charset', 'x-user-defined');
            req.send();
            var fileContents = BinaryToArray(req.responseBody).toArray();
            var x =  loadComplete(fileContents);
            req.onload = function(){c.call(x)}
        } else {
            req.overrideMimeType('text/plain; charset=x-user-defined');
            req.send();
            var x = loadComplete(req.responseText);
            req.onload = function(){c.call(x)}
        }
    }

    function loadComplete(data) {
        if (isCompleteProcessRequired) {
            var zipfile = new ZipFile(new BA(data, Endian.LITTLE));
            ZIP_CACHE[_zipUrl] = zipfile;
            dataFromZip(zipfile);
        }
    }

    function dataFromZip(zip) {
        if (zip && _entryUrl) {
            data = getZipEntry(zip, _entryUrl);
        }
    }

    function getZipEntry(zip, entryUrl) {
        if (ZIP_ENTRY_REG.test(entryUrl)) {
            var result = ZIP_ENTRY_REG.exec(entryUrl);
            var outerEntry = result[1];
            var innerEntry = result[2];
            var innerZip = new ZipFile(zip.getInput(zip.getEntry(outerEntry)));
            return getZipEntry(innerZip, innerEntry);
        } else {
            var entry = zip.getEntry(entryUrl);
            if (entry) {
                return zip.getInput(entry);
            } else {
                throw "Requested file was not found in the archive";
            }
        }
    }

    function appendChild(node, text) {
        if (null === node.canHaveChildren || node.canHaveChildren) {
            node.appendChild(document.createTextNode(text));
        } else {
            node.text = text;
        }
    }

    function getFileExtension(filename) {
        return (/[.]/.exec(filename)) && /[^.]+$/.exec(filename)[0] || '';
    }

	function _utf8_decode(utftext) {
		if (utftext.charCodeAt(0) == 0xef && utftext.charCodeAt(1) == 0xbb && utftext.charCodeAt(2) == 0xbf) {
			utftext = utftext.substr(3)
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
			while ( i < utftext.length ) {
				c = utftext.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		} 
		return utftext;
	}
	
    return {
        load : function(url, c) {
            isCompleteProcessRequired = false;
            switch(true) {
                case ZIP_ENTRY_REG.test(url):
                    var result = ZIP_ENTRY_REG.exec(url);
                    _zipUrl = result[1];
                    _entryUrl = result[2];
                    var zip = ZIP_CACHE[_zipUrl];
                    if (zip) {
                        dataFromZip(zip);
                    } else {
                        isCompleteProcessRequired = true;
                        loadBinaryResource(url, function(){c.call(url)});
                    }
                    break;
                case ZIP_FILE_REG.test(url):
                    isCompleteProcessRequired = true;
                    _zipUrl = url;
                    _entryDataFormat = 'Text';
                    loadBinaryResource(url, function(){c.call(url)});
                    break;
                default:
                    loadBinaryResource(url, function(){c.call(url)});
                    break;
            }
            if (data) {
                data.position(0);
                c.call(_utf8_decode(data.readBytes(0, data.length())))
                return _utf8_decode(data.readBytes(0, data.length()));
            } else {
                return '';
            }
        },
        getEntries : function(zipName) {
return ZIP_CACHE[zipName] && ZIP_CACHE[zipName].entries() || [];
},
        loadImage : function(url, c) {
            this.load(url, function(){
              var data = this;
                if (data) {
                    var tmp = "data:";
                    switch(getFileExtension(url).toLowerCase()) {
                        case 'gif' :
                            tmp += "image/gif;base64,";
                            break;
                        case 'png' :
                            tmp += "image/png;base64,";
                            break;
                        case 'jpg':
                        case 'jpeg':
                            tmp += "image/jpeg;base64,";
                            break;
                    }
                    tmp += Base64(data);
                    c.call(tmp);
                    return tmp;
                }
            });

          return "";
        },
        loadCSS : function(url) {
            var data = _utf8_decode(this.load(url));
            if (data) {
                var pa= document.getElementsByTagName('head')[0] ;
                var el= document.createElement('style');
                el.type= 'text/css';
                el.media= 'screen';
                if(el.styleSheet) el.styleSheet.cssText = data;// IE method
                else el.appendChild(document.createTextNode(data));// others
                pa.appendChild(el);
            }
        },
        loadScript : function(url,encoding) {
            var data = _utf8_decode(this.load(url));
            if (data) {
                var fileRef = window.document.createElement("script");
                    fileRef.setAttribute("type", "text/javascript");
					 fileRef.setAttribute("charset", encoding);
                if (isIE()) { // IE
                    eval(data);
                } else{
                    var head = document.getElementsByTagName("head")[0] || document.documentElement;
                    head.insertBefore(fileRef, head.firstChild);
                    appendChild(fileRef, data);
                }
            }
        }
    }
};

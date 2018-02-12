(function () {
    var CryptoJS = CryptoJS || function (u, p) {
            var d = {}, l = d.lib = {}, s = function () {
            }, t = l.Base = {
                extend: function (a) {
                    s.prototype = this;
                    var c = new s;
                    a && c.mixIn(a);
                    c.hasOwnProperty("init") || (c.init = function () {
                        c.$super.init.apply(this, arguments)
                    });
                    c.init.prototype = c;
                    c.$super = this;
                    return c
                }, create: function () {
                    var a = this.extend();
                    a.init.apply(a, arguments);
                    return a
                }, init: function () {
                }, mixIn: function (a) {
                    for (var c in a) {
                        a.hasOwnProperty(c) && (this[c] = a[c])
                    }
                    a.hasOwnProperty("toString") && (this.toString = a.toString)
                }, clone: function () {
                    return this.init.prototype.extend(this)
                }
            }, r = l.WordArray = t.extend({
                init: function (a, c) {
                    a = this.words = a || [];
                    this.sigBytes = c != p ? c : 4 * a.length
                }, toString: function (a) {
                    return (a || v).stringify(this)
                }, concat: function (a) {
                    var c = this.words, e = a.words, j = this.sigBytes;
                    a = a.sigBytes;
                    this.clamp();
                    if (j % 4) {
                        for (var k = 0; k < a; k++) {
                            c[j + k >>> 2] |= (e[k >>> 2] >>> 24 - 8 * (k % 4) & 255) << 24 - 8 * ((j + k) % 4)
                        }
                    } else {
                        if (65535 < e.length) {
                            for (k = 0; k < a; k += 4) {
                                c[j + k >>> 2] = e[k >>> 2]
                            }
                        } else {
                            c.push.apply(c, e)
                        }
                    }
                    this.sigBytes += a;
                    return this
                }, clamp: function () {
                    var a = this.words, c = this.sigBytes;
                    a[c >>> 2] &= 4294967295 << 32 - 8 * (c % 4);
                    a.length = u.ceil(c / 4)
                }, clone: function () {
                    var a = t.clone.call(this);
                    a.words = this.words.slice(0);
                    return a
                }, random: function (a) {
                    for (var c = [], e = 0; e < a; e += 4) {
                        c.push(4294967296 * u.random() | 0)
                    }
                    return new r.init(c, a)
                }
            }), w = d.enc = {}, v = w.Hex = {
                stringify: function (a) {
                    var c = a.words;
                    a = a.sigBytes;
                    for (var e = [], j = 0; j < a; j++) {
                        var k = c[j >>> 2] >>> 24 - 8 * (j % 4) & 255;
                        e.push((k >>> 4).toString(16));
                        e.push((k & 15).toString(16))
                    }
                    return e.join("")
                }, parse: function (a) {
                    for (var c = a.length, e = [], j = 0; j < c; j += 2) {
                        e[j >>> 3] |= parseInt(a.substr(j, 2), 16) << 24 - 4 * (j % 8)
                    }
                    return new r.init(e, c / 2)
                }
            }, b = w.Latin1 = {
                stringify: function (a) {
                    var c = a.words;
                    a = a.sigBytes;
                    for (var e = [], j = 0; j < a; j++) {
                        e.push(String.fromCharCode(c[j >>> 2] >>> 24 - 8 * (j % 4) & 255))
                    }
                    return e.join("")
                }, parse: function (a) {
                    for (var c = a.length, e = [], j = 0; j < c; j++) {
                        e[j >>> 2] |= (a.charCodeAt(j) & 255) << 24 - 8 * (j % 4)
                    }
                    return new r.init(e, c)
                }
            }, x = w.Utf8 = {
                stringify: function (a) {
                    try {
                        return decodeURIComponent(escape(b.stringify(a)))
                    } catch (c) {
                        throw Error("Malformed UTF-8 data")
                    }
                }, parse: function (a) {
                    return b.parse(unescape(encodeURIComponent(a)))
                }
            }, q = l.BufferedBlockAlgorithm = t.extend({
                reset: function () {
                    this._data = new r.init;
                    this._nDataBytes = 0
                }, _append: function (a) {
                    "string" == typeof a && (a = x.parse(a));
                    this._data.concat(a);
                    this._nDataBytes += a.sigBytes
                }, _process: function (a) {
                    var c = this._data, e = c.words, j = c.sigBytes, k = this.blockSize, b = j / (4 * k),
                        b = a ? u.ceil(b) : u.max((b | 0) - this._minBufferSize, 0);
                    a = b * k;
                    j = u.min(4 * a, j);
                    if (a) {
                        for (var q = 0; q < a; q += k) {
                            this._doProcessBlock(e, q)
                        }
                        q = e.splice(0, a);
                        c.sigBytes -= j
                    }
                    return new r.init(q, j)
                }, clone: function () {
                    var a = t.clone.call(this);
                    a._data = this._data.clone();
                    return a
                }, _minBufferSize: 0
            });
            l.Hasher = q.extend({
                cfg: t.extend(), init: function (a) {
                    this.cfg = this.cfg.extend(a);
                    this.reset()
                }, reset: function () {
                    q.reset.call(this);
                    this._doReset()
                }, update: function (a) {
                    this._append(a);
                    this._process();
                    return this
                }, finalize: function (a) {
                    a && this._append(a);
                    return this._doFinalize()
                }, blockSize: 16, _createHelper: function (a) {
                    return function (b, e) {
                        return (new a.init(e)).finalize(b)
                    }
                }, _createHmacHelper: function (a) {
                    return function (b, e) {
                        return (new n.HMAC.init(a, e)).finalize(b)
                    }
                }
            });
            var n = d.algo = {};
            return d
        }(Math);
    (function () {
        var u = CryptoJS, p = u.lib.WordArray;
        u.enc.Base64 = {
            stringify: function (d) {
                var l = d.words, p = d.sigBytes, t = this._map;
                d.clamp();
                d = [];
                for (var r = 0; r < p; r += 3) {
                    for (var w = (l[r >>> 2] >>> 24 - 8 * (r % 4) & 255) << 16 | (l[r + 1 >>> 2] >>> 24 - 8 * ((r + 1) % 4) & 255) << 8 | l[r + 2 >>> 2] >>> 24 - 8 * ((r + 2) % 4) & 255, v = 0; 4 > v && r + 0.75 * v < p; v++) {
                        d.push(t.charAt(w >>> 6 * (3 - v) & 63))
                    }
                }
                if (l = t.charAt(64)) {
                    for (; d.length % 4;) {
                        d.push(l)
                    }
                }
                return d.join("")
            }, parse: function (d) {
                var l = d.length, s = this._map, t = s.charAt(64);
                t && (t = d.indexOf(t), -1 != t && (l = t));
                for (var t = [], r = 0, w = 0; w < l; w++) {
                    if (w % 4) {
                        var v = s.indexOf(d.charAt(w - 1)) << 2 * (w % 4),
                            b = s.indexOf(d.charAt(w)) >>> 6 - 2 * (w % 4);
                        t[r >>> 2] |= (v | b) << 24 - 8 * (r % 4);
                        r++
                    }
                }
                return p.create(t, r)
            }, _map: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
        }
    })();
    (function (u) {
        function p(b, n, a, c, e, j, k) {
            b = b + (n & a | ~n & c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }

        function d(b, n, a, c, e, j, k) {
            b = b + (n & c | a & ~c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }

        function l(b, n, a, c, e, j, k) {
            b = b + (n ^ a ^ c) + e + k;
            return (b << j | b >>> 32 - j) + n
        }

        function s(b, n, a, c, e, j, k) {
            b = b + (a ^ (n | ~c)) + e + k;
            return (b << j | b >>> 32 - j) + n
        }

        for (var t = CryptoJS, r = t.lib, w = r.WordArray, v = r.Hasher, r = t.algo, b = [], x = 0; 64 > x; x++) {
            b[x] = 4294967296 * u.abs(u.sin(x + 1)) | 0
        }
        r = r.MD5 = v.extend({
            _doReset: function () {
                this._hash = new w.init([1732584193, 4023233417, 2562383102, 271733878])
            }, _doProcessBlock: function (q, n) {
                for (var a = 0; 16 > a; a++) {
                    var c = n + a, e = q[c];
                    q[c] = (e << 8 | e >>> 24) & 16711935 | (e << 24 | e >>> 8) & 4278255360
                }
                var a = this._hash.words, c = q[n + 0], e = q[n + 1], j = q[n + 2], k = q[n + 3], z = q[n + 4],
                    r = q[n + 5], t = q[n + 6], w = q[n + 7], v = q[n + 8], A = q[n + 9], B = q[n + 10], C = q[n + 11],
                    u = q[n + 12], D = q[n + 13], E = q[n + 14], x = q[n + 15], f = a[0], m = a[1], g = a[2], h = a[3],
                    f = p(f, m, g, h, c, 7, b[0]), h = p(h, f, m, g, e, 12, b[1]), g = p(g, h, f, m, j, 17, b[2]),
                    m = p(m, g, h, f, k, 22, b[3]), f = p(f, m, g, h, z, 7, b[4]), h = p(h, f, m, g, r, 12, b[5]),
                    g = p(g, h, f, m, t, 17, b[6]), m = p(m, g, h, f, w, 22, b[7]), f = p(f, m, g, h, v, 7, b[8]),
                    h = p(h, f, m, g, A, 12, b[9]), g = p(g, h, f, m, B, 17, b[10]), m = p(m, g, h, f, C, 22, b[11]),
                    f = p(f, m, g, h, u, 7, b[12]), h = p(h, f, m, g, D, 12, b[13]), g = p(g, h, f, m, E, 17, b[14]),
                    m = p(m, g, h, f, x, 22, b[15]), f = d(f, m, g, h, e, 5, b[16]), h = d(h, f, m, g, t, 9, b[17]),
                    g = d(g, h, f, m, C, 14, b[18]), m = d(m, g, h, f, c, 20, b[19]), f = d(f, m, g, h, r, 5, b[20]),
                    h = d(h, f, m, g, B, 9, b[21]), g = d(g, h, f, m, x, 14, b[22]), m = d(m, g, h, f, z, 20, b[23]),
                    f = d(f, m, g, h, A, 5, b[24]), h = d(h, f, m, g, E, 9, b[25]), g = d(g, h, f, m, k, 14, b[26]),
                    m = d(m, g, h, f, v, 20, b[27]), f = d(f, m, g, h, D, 5, b[28]), h = d(h, f, m, g, j, 9, b[29]),
                    g = d(g, h, f, m, w, 14, b[30]), m = d(m, g, h, f, u, 20, b[31]), f = l(f, m, g, h, r, 4, b[32]),
                    h = l(h, f, m, g, v, 11, b[33]), g = l(g, h, f, m, C, 16, b[34]), m = l(m, g, h, f, E, 23, b[35]),
                    f = l(f, m, g, h, e, 4, b[36]), h = l(h, f, m, g, z, 11, b[37]), g = l(g, h, f, m, w, 16, b[38]),
                    m = l(m, g, h, f, B, 23, b[39]), f = l(f, m, g, h, D, 4, b[40]), h = l(h, f, m, g, c, 11, b[41]),
                    g = l(g, h, f, m, k, 16, b[42]), m = l(m, g, h, f, t, 23, b[43]), f = l(f, m, g, h, A, 4, b[44]),
                    h = l(h, f, m, g, u, 11, b[45]), g = l(g, h, f, m, x, 16, b[46]), m = l(m, g, h, f, j, 23, b[47]),
                    f = s(f, m, g, h, c, 6, b[48]), h = s(h, f, m, g, w, 10, b[49]), g = s(g, h, f, m, E, 15, b[50]),
                    m = s(m, g, h, f, r, 21, b[51]), f = s(f, m, g, h, u, 6, b[52]), h = s(h, f, m, g, k, 10, b[53]),
                    g = s(g, h, f, m, B, 15, b[54]), m = s(m, g, h, f, e, 21, b[55]), f = s(f, m, g, h, v, 6, b[56]),
                    h = s(h, f, m, g, x, 10, b[57]), g = s(g, h, f, m, t, 15, b[58]), m = s(m, g, h, f, D, 21, b[59]),
                    f = s(f, m, g, h, z, 6, b[60]), h = s(h, f, m, g, C, 10, b[61]), g = s(g, h, f, m, j, 15, b[62]),
                    m = s(m, g, h, f, A, 21, b[63]);
                a[0] = a[0] + f | 0;
                a[1] = a[1] + m | 0;
                a[2] = a[2] + g | 0;
                a[3] = a[3] + h | 0
            }, _doFinalize: function () {
                var b = this._data, n = b.words, a = 8 * this._nDataBytes, c = 8 * b.sigBytes;
                n[c >>> 5] |= 128 << 24 - c % 32;
                var e = u.floor(a / 4294967296);
                n[(c + 64 >>> 9 << 4) + 15] = (e << 8 | e >>> 24) & 16711935 | (e << 24 | e >>> 8) & 4278255360;
                n[(c + 64 >>> 9 << 4) + 14] = (a << 8 | a >>> 24) & 16711935 | (a << 24 | a >>> 8) & 4278255360;
                b.sigBytes = 4 * (n.length + 1);
                this._process();
                b = this._hash;
                n = b.words;
                for (a = 0; 4 > a; a++) {
                    c = n[a], n[a] = (c << 8 | c >>> 24) & 16711935 | (c << 24 | c >>> 8) & 4278255360
                }
                return b
            }, clone: function () {
                var b = v.clone.call(this);
                b._hash = this._hash.clone();
                return b
            }
        });
        t.MD5 = v._createHelper(r);
        t.HmacMD5 = v._createHmacHelper(r)
    })(Math);
    (function () {
        var u = CryptoJS, p = u.lib, d = p.Base, l = p.WordArray, p = u.algo, s = p.EvpKDF = d.extend({
            cfg: d.extend({keySize: 4, hasher: p.MD5, iterations: 1}), init: function (d) {
                this.cfg = this.cfg.extend(d)
            }, compute: function (d, r) {
                for (var p = this.cfg, s = p.hasher.create(), b = l.create(), u = b.words, q = p.keySize, p = p.iterations; u.length < q;) {
                    n && s.update(n);
                    var n = s.update(d).finalize(r);
                    s.reset();
                    for (var a = 1; a < p; a++) {
                        n = s.finalize(n), s.reset()
                    }
                    b.concat(n)
                }
                b.sigBytes = 4 * q;
                return b
            }
        });
        u.EvpKDF = function (d, l, p) {
            return s.create(p).compute(d, l)
        }
    })();
    CryptoJS.lib.Cipher || function (u) {
        var p = CryptoJS, d = p.lib, l = d.Base, s = d.WordArray, t = d.BufferedBlockAlgorithm, r = p.enc.Base64,
            w = p.algo.EvpKDF, v = d.Cipher = t.extend({
                cfg: l.extend(), createEncryptor: function (e, a) {
                    return this.create(this._ENC_XFORM_MODE, e, a)
                }, createDecryptor: function (e, a) {
                    return this.create(this._DEC_XFORM_MODE, e, a)
                }, init: function (e, a, b) {
                    this.cfg = this.cfg.extend(b);
                    this._xformMode = e;
                    this._key = a;
                    this.reset()
                }, reset: function () {
                    t.reset.call(this);
                    this._doReset()
                }, process: function (e) {
                    this._append(e);
                    return this._process()
                }, finalize: function (e) {
                    e && this._append(e);
                    return this._doFinalize()
                }, keySize: 4, ivSize: 4, _ENC_XFORM_MODE: 1, _DEC_XFORM_MODE: 2, _createHelper: function (e) {
                    return {
                        encrypt: function (b, k, d) {
                            return ("string" == typeof k ? c : a).encrypt(e, b, k, d)
                        }, decrypt: function (b, k, d) {
                            return ("string" == typeof k ? c : a).decrypt(e, b, k, d)
                        }
                    }
                }
            });
        d.StreamCipher = v.extend({
            _doFinalize: function () {
                return this._process(!0)
            }, blockSize: 1
        });
        var b = p.mode = {}, x = function (e, a, b) {
            var c = this._iv;
            c ? this._iv = u : c = this._prevBlock;
            for (var d = 0; d < b; d++) {
                e[a + d] ^= c[d]
            }
        }, q = (d.BlockCipherMode = l.extend({
            createEncryptor: function (e, a) {
                return this.Encryptor.create(e, a)
            }, createDecryptor: function (e, a) {
                return this.Decryptor.create(e, a)
            }, init: function (e, a) {
                this._cipher = e;
                this._iv = a
            }
        })).extend();
        q.Encryptor = q.extend({
            processBlock: function (e, a) {
                var b = this._cipher, c = b.blockSize;
                x.call(this, e, a, c);
                b.encryptBlock(e, a);
                this._prevBlock = e.slice(a, a + c)
            }
        });
        q.Decryptor = q.extend({
            processBlock: function (e, a) {
                var b = this._cipher, c = b.blockSize, d = e.slice(a, a + c);
                b.decryptBlock(e, a);
                x.call(this, e, a, c);
                this._prevBlock = d
            }
        });
        b = b.CBC = q;
        q = (p.pad = {}).Pkcs7 = {
            pad: function (a, b) {
                for (var c = 4 * b, c = c - a.sigBytes % c, d = c << 24 | c << 16 | c << 8 | c, l = [], n = 0; n < c; n += 4) {
                    l.push(d)
                }
                c = s.create(l, c);
                a.concat(c)
            }, unpad: function (a) {
                a.sigBytes -= a.words[a.sigBytes - 1 >>> 2] & 255
            }
        };
        d.BlockCipher = v.extend({
            cfg: v.cfg.extend({mode: b, padding: q}), reset: function () {
                v.reset.call(this);
                var a = this.cfg, b = a.iv, a = a.mode;
                if (this._xformMode == this._ENC_XFORM_MODE) {
                    var c = a.createEncryptor
                } else {
                    c = a.createDecryptor, this._minBufferSize = 1
                }
                this._mode = c.call(a, this, b && b.words)
            }, _doProcessBlock: function (a, b) {
                this._mode.processBlock(a, b)
            }, _doFinalize: function () {
                var a = this.cfg.padding;
                if (this._xformMode == this._ENC_XFORM_MODE) {
                    a.pad(this._data, this.blockSize);
                    var b = this._process(!0)
                } else {
                    b = this._process(!0), a.unpad(b)
                }
                return b
            }, blockSize: 4
        });
        var n = d.CipherParams = l.extend({
            init: function (a) {
                this.mixIn(a)
            }, toString: function (a) {
                return (a || this.formatter).stringify(this)
            }
        }), b = (p.format = {}).OpenSSL = {
            stringify: function (a) {
                var b = a.ciphertext;
                a = a.salt;
                return (a ? s.create([1398893684, 1701076831]).concat(a).concat(b) : b).toString(r)
            }, parse: function (a) {
                a = r.parse(a);
                var b = a.words;
                if (1398893684 == b[0] && 1701076831 == b[1]) {
                    var c = s.create(b.slice(2, 4));
                    b.splice(0, 4);
                    a.sigBytes -= 16
                }
                return n.create({ciphertext: a, salt: c})
            }
        }, a = d.SerializableCipher = l.extend({
            cfg: l.extend({format: b}), encrypt: function (a, b, c, d) {
                d = this.cfg.extend(d);
                var l = a.createEncryptor(c, d);
                b = l.finalize(b);
                l = l.cfg;
                return n.create({
                    ciphertext: b,
                    key: c,
                    iv: l.iv,
                    algorithm: a,
                    mode: l.mode,
                    padding: l.padding,
                    blockSize: a.blockSize,
                    formatter: d.format
                })
            }, decrypt: function (a, b, c, d) {
                d = this.cfg.extend(d);
                b = this._parse(b, d.format);
                return a.createDecryptor(c, d).finalize(b.ciphertext)
            }, _parse: function (a, b) {
                return "string" == typeof a ? b.parse(a, this) : a
            }
        }), p = (p.kdf = {}).OpenSSL = {
            execute: function (a, b, c, d) {
                d || (d = s.random(8));
                a = w.create({keySize: b + c}).compute(a, d);
                c = s.create(a.words.slice(b), 4 * c);
                a.sigBytes = 4 * b;
                return n.create({key: a, iv: c, salt: d})
            }
        }, c = d.PasswordBasedCipher = a.extend({
            cfg: a.cfg.extend({kdf: p}), encrypt: function (b, c, d, l) {
                l = this.cfg.extend(l);
                d = l.kdf.execute(d, b.keySize, b.ivSize);
                l.iv = d.iv;
                b = a.encrypt.call(this, b, c, d.key, l);
                b.mixIn(d);
                return b
            }, decrypt: function (b, c, d, l) {
                l = this.cfg.extend(l);
                c = this._parse(c, l.format);
                d = l.kdf.execute(d, b.keySize, b.ivSize, c.salt);
                l.iv = d.iv;
                return a.decrypt.call(this, b, c, d.key, l)
            }
        })
    }();
    (function () {
        for (var u = CryptoJS, p = u.lib.BlockCipher, d = u.algo, l = [], s = [], t = [], r = [], w = [], v = [], b = [], x = [], q = [], n = [], a = [], c = 0; 256 > c; c++) {
            a[c] = 128 > c ? c << 1 : c << 1 ^ 283
        }
        for (var e = 0, j = 0, c = 0; 256 > c; c++) {
            var k = j ^ j << 1 ^ j << 2 ^ j << 3 ^ j << 4, k = k >>> 8 ^ k & 255 ^ 99;
            l[e] = k;
            s[k] = e;
            var z = a[e], F = a[z], G = a[F], y = 257 * a[k] ^ 16843008 * k;
            t[e] = y << 24 | y >>> 8;
            r[e] = y << 16 | y >>> 16;
            w[e] = y << 8 | y >>> 24;
            v[e] = y;
            y = 16843009 * G ^ 65537 * F ^ 257 * z ^ 16843008 * e;
            b[k] = y << 24 | y >>> 8;
            x[k] = y << 16 | y >>> 16;
            q[k] = y << 8 | y >>> 24;
            n[k] = y;
            e ? (e = z ^ a[a[a[G ^ z]]], j ^= a[a[j]]) : e = j = 1
        }
        var H = [0, 1, 2, 4, 8, 16, 32, 64, 128, 27, 54], d = d.AES = p.extend({
            _doReset: function () {
                for (var a = this._key, c = a.words, d = a.sigBytes / 4, a = 4 * ((this._nRounds = d + 6) + 1), e = this._keySchedule = [], j = 0; j < a; j++) {
                    if (j < d) {
                        e[j] = c[j]
                    } else {
                        var k = e[j - 1];
                        j % d ? 6 < d && 4 == j % d && (k = l[k >>> 24] << 24 | l[k >>> 16 & 255] << 16 | l[k >>> 8 & 255] << 8 | l[k & 255]) : (k = k << 8 | k >>> 24, k = l[k >>> 24] << 24 | l[k >>> 16 & 255] << 16 | l[k >>> 8 & 255] << 8 | l[k & 255], k ^= H[j / d | 0] << 24);
                        e[j] = e[j - d] ^ k
                    }
                }
                c = this._invKeySchedule = [];
                for (d = 0; d < a; d++) {
                    j = a - d, k = d % 4 ? e[j] : e[j - 4], c[d] = 4 > d || 4 >= j ? k : b[l[k >>> 24]] ^ x[l[k >>> 16 & 255]] ^ q[l[k >>> 8 & 255]] ^ n[l[k & 255]]
                }
            }, encryptBlock: function (a, b) {
                this._doCryptBlock(a, b, this._keySchedule, t, r, w, v, l)
            }, decryptBlock: function (a, c) {
                var d = a[c + 1];
                a[c + 1] = a[c + 3];
                a[c + 3] = d;
                this._doCryptBlock(a, c, this._invKeySchedule, b, x, q, n, s);
                d = a[c + 1];
                a[c + 1] = a[c + 3];
                a[c + 3] = d
            }, _doCryptBlock: function (a, b, c, d, e, j, l, f) {
                for (var m = this._nRounds, g = a[b] ^ c[0], h = a[b + 1] ^ c[1], k = a[b + 2] ^ c[2], n = a[b + 3] ^ c[3], p = 4, r = 1; r < m; r++) {
                    var q = d[g >>> 24] ^ e[h >>> 16 & 255] ^ j[k >>> 8 & 255] ^ l[n & 255] ^ c[p++],
                        s = d[h >>> 24] ^ e[k >>> 16 & 255] ^ j[n >>> 8 & 255] ^ l[g & 255] ^ c[p++],
                        t = d[k >>> 24] ^ e[n >>> 16 & 255] ^ j[g >>> 8 & 255] ^ l[h & 255] ^ c[p++],
                        n = d[n >>> 24] ^ e[g >>> 16 & 255] ^ j[h >>> 8 & 255] ^ l[k & 255] ^ c[p++], g = q, h = s,
                        k = t
                }
                q = (f[g >>> 24] << 24 | f[h >>> 16 & 255] << 16 | f[k >>> 8 & 255] << 8 | f[n & 255]) ^ c[p++];
                s = (f[h >>> 24] << 24 | f[k >>> 16 & 255] << 16 | f[n >>> 8 & 255] << 8 | f[g & 255]) ^ c[p++];
                t = (f[k >>> 24] << 24 | f[n >>> 16 & 255] << 16 | f[g >>> 8 & 255] << 8 | f[h & 255]) ^ c[p++];
                n = (f[n >>> 24] << 24 | f[g >>> 16 & 255] << 16 | f[h >>> 8 & 255] << 8 | f[k & 255]) ^ c[p++];
                a[b] = q;
                a[b + 1] = s;
                a[b + 2] = t;
                a[b + 3] = n
            }, keySize: 8
        });
        u.AES = p._createHelper(d)
    })();
    var CryptoJS = CryptoJS || function (h, s) {
            var f = {}, t = f.lib = {}, g = function () {
            }, j = t.Base = {
                extend: function (a) {
                    g.prototype = this;
                    var c = new g;
                    a && c.mixIn(a);
                    c.hasOwnProperty("init") || (c.init = function () {
                        c.$super.init.apply(this, arguments)
                    });
                    c.init.prototype = c;
                    c.$super = this;
                    return c
                }, create: function () {
                    var a = this.extend();
                    a.init.apply(a, arguments);
                    return a
                }, init: function () {
                }, mixIn: function (a) {
                    for (var c in a) {
                        a.hasOwnProperty(c) && (this[c] = a[c])
                    }
                    a.hasOwnProperty("toString") && (this.toString = a.toString)
                }, clone: function () {
                    return this.init.prototype.extend(this)
                }
            }, q = t.WordArray = j.extend({
                init: function (a, c) {
                    a = this.words = a || [];
                    this.sigBytes = c != s ? c : 4 * a.length
                }, toString: function (a) {
                    return (a || u).stringify(this)
                }, concat: function (a) {
                    var c = this.words, d = a.words, b = this.sigBytes;
                    a = a.sigBytes;
                    this.clamp();
                    if (b % 4) {
                        for (var e = 0; e < a; e++) {
                            c[b + e >>> 2] |= (d[e >>> 2] >>> 24 - 8 * (e % 4) & 255) << 24 - 8 * ((b + e) % 4)
                        }
                    } else {
                        if (65535 < d.length) {
                            for (e = 0; e < a; e += 4) {
                                c[b + e >>> 2] = d[e >>> 2]
                            }
                        } else {
                            c.push.apply(c, d)
                        }
                    }
                    this.sigBytes += a;
                    return this
                }, clamp: function () {
                    var a = this.words, c = this.sigBytes;
                    a[c >>> 2] &= 4294967295 << 32 - 8 * (c % 4);
                    a.length = h.ceil(c / 4)
                }, clone: function () {
                    var a = j.clone.call(this);
                    a.words = this.words.slice(0);
                    return a
                }, random: function (a) {
                    for (var c = [], d = 0; d < a; d += 4) {
                        c.push(4294967296 * h.random() | 0)
                    }
                    return new q.init(c, a)
                }
            }), v = f.enc = {}, u = v.Hex = {
                stringify: function (a) {
                    var c = a.words;
                    a = a.sigBytes;
                    for (var d = [], b = 0; b < a; b++) {
                        var e = c[b >>> 2] >>> 24 - 8 * (b % 4) & 255;
                        d.push((e >>> 4).toString(16));
                        d.push((e & 15).toString(16))
                    }
                    return d.join("")
                }, parse: function (a) {
                    for (var c = a.length, d = [], b = 0; b < c; b += 2) {
                        d[b >>> 3] |= parseInt(a.substr(b, 2), 16) << 24 - 4 * (b % 8)
                    }
                    return new q.init(d, c / 2)
                }
            }, k = v.Latin1 = {
                stringify: function (a) {
                    var c = a.words;
                    a = a.sigBytes;
                    for (var d = [], b = 0; b < a; b++) {
                        d.push(String.fromCharCode(c[b >>> 2] >>> 24 - 8 * (b % 4) & 255))
                    }
                    return d.join("")
                }, parse: function (a) {
                    for (var c = a.length, d = [], b = 0; b < c; b++) {
                        d[b >>> 2] |= (a.charCodeAt(b) & 255) << 24 - 8 * (b % 4)
                    }
                    return new q.init(d, c)
                }
            }, l = v.Utf8 = {
                stringify: function (a) {
                    try {
                        return decodeURIComponent(escape(k.stringify(a)))
                    } catch (c) {
                        throw Error("Malformed UTF-8 data")
                    }
                }, parse: function (a) {
                    return k.parse(unescape(encodeURIComponent(a)))
                }
            }, x = t.BufferedBlockAlgorithm = j.extend({
                reset: function () {
                    this._data = new q.init;
                    this._nDataBytes = 0
                }, _append: function (a) {
                    "string" == typeof a && (a = l.parse(a));
                    this._data.concat(a);
                    this._nDataBytes += a.sigBytes
                }, _process: function (a) {
                    var c = this._data, d = c.words, b = c.sigBytes, e = this.blockSize, f = b / (4 * e),
                        f = a ? h.ceil(f) : h.max((f | 0) - this._minBufferSize, 0);
                    a = f * e;
                    b = h.min(4 * a, b);
                    if (a) {
                        for (var m = 0; m < a; m += e) {
                            this._doProcessBlock(d, m)
                        }
                        m = d.splice(0, a);
                        c.sigBytes -= b
                    }
                    return new q.init(m, b)
                }, clone: function () {
                    var a = j.clone.call(this);
                    a._data = this._data.clone();
                    return a
                }, _minBufferSize: 0
            });
            t.Hasher = x.extend({
                cfg: j.extend(), init: function (a) {
                    this.cfg = this.cfg.extend(a);
                    this.reset()
                }, reset: function () {
                    x.reset.call(this);
                    this._doReset()
                }, update: function (a) {
                    this._append(a);
                    this._process();
                    return this
                }, finalize: function (a) {
                    a && this._append(a);
                    return this._doFinalize()
                }, blockSize: 16, _createHelper: function (a) {
                    return function (c, d) {
                        return (new a.init(d)).finalize(c)
                    }
                }, _createHmacHelper: function (a) {
                    return function (c, d) {
                        return (new w.HMAC.init(a, d)).finalize(c)
                    }
                }
            });
            var w = f.algo = {};
            return f
        }(Math);
    (function (h) {
        for (var s = CryptoJS, f = s.lib, t = f.WordArray, g = f.Hasher, f = s.algo, j = [], q = [], v = function (a) {
            return 4294967296 * (a - (a | 0)) | 0
        }, u = 2, k = 0; 64 > k;) {
            var l;
            a:{
                l = u;
                for (var x = h.sqrt(l), w = 2; w <= x; w++) {
                    if (!(l % w)) {
                        l = !1;
                        break a
                    }
                }
                l = !0
            }
            l && (8 > k && (j[k] = v(h.pow(u, 0.5))), q[k] = v(h.pow(u, 1 / 3)), k++);
            u++
        }
        var a = [], f = f.SHA256 = g.extend({
            _doReset: function () {
                this._hash = new t.init(j.slice(0))
            }, _doProcessBlock: function (c, d) {
                for (var b = this._hash.words, e = b[0], f = b[1], m = b[2], h = b[3], p = b[4], j = b[5], k = b[6], l = b[7], n = 0; 64 > n; n++) {
                    if (16 > n) {
                        a[n] = c[d + n] | 0
                    } else {
                        var r = a[n - 15], g = a[n - 2];
                        a[n] = ((r << 25 | r >>> 7) ^ (r << 14 | r >>> 18) ^ r >>> 3) + a[n - 7] + ((g << 15 | g >>> 17) ^ (g << 13 | g >>> 19) ^ g >>> 10) + a[n - 16]
                    }
                    r = l + ((p << 26 | p >>> 6) ^ (p << 21 | p >>> 11) ^ (p << 7 | p >>> 25)) + (p & j ^ ~p & k) + q[n] + a[n];
                    g = ((e << 30 | e >>> 2) ^ (e << 19 | e >>> 13) ^ (e << 10 | e >>> 22)) + (e & f ^ e & m ^ f & m);
                    l = k;
                    k = j;
                    j = p;
                    p = h + r | 0;
                    h = m;
                    m = f;
                    f = e;
                    e = r + g | 0
                }
                b[0] = b[0] + e | 0;
                b[1] = b[1] + f | 0;
                b[2] = b[2] + m | 0;
                b[3] = b[3] + h | 0;
                b[4] = b[4] + p | 0;
                b[5] = b[5] + j | 0;
                b[6] = b[6] + k | 0;
                b[7] = b[7] + l | 0
            }, _doFinalize: function () {
                var a = this._data, d = a.words, b = 8 * this._nDataBytes, e = 8 * a.sigBytes;
                d[e >>> 5] |= 128 << 24 - e % 32;
                d[(e + 64 >>> 9 << 4) + 14] = h.floor(b / 4294967296);
                d[(e + 64 >>> 9 << 4) + 15] = b;
                a.sigBytes = 4 * d.length;
                this._process();
                return this._hash
            }, clone: function () {
                var a = g.clone.call(this);
                a._hash = this._hash.clone();
                return a
            }
        });
        s.SHA256 = g._createHelper(f);
        s.HmacSHA256 = g._createHmacHelper(f)
    })(Math);
    CryptoJS.mode.CFB = function () {
        function g(c, b, e, a) {
            var d = this._iv;
            d ? (d = d.slice(0), this._iv = void 0) : d = this._prevBlock;
            a.encryptBlock(d, 0);
            for (a = 0; a < e; a++) {
                c[b + a] ^= d[a]
            }
        }

        var f = CryptoJS.lib.BlockCipherMode.extend();
        f.Encryptor = f.extend({
            processBlock: function (c, b) {
                var e = this._cipher, a = e.blockSize;
                g.call(this, c, b, a, e);
                this._prevBlock = c.slice(b, b + a)
            }
        });
        f.Decryptor = f.extend({
            processBlock: function (c, b) {
                var e = this._cipher, a = e.blockSize, d = c.slice(b, b + a);
                g.call(this, c, b, a, e);
                this._prevBlock = d
            }
        });
        return f
    }();
    CryptoJS.pad.NoPadding = {
        pad: function () {
        }, unpad: function () {
        }
    };
    var authenticated = Cookies.getJSON("authenticated");
    var userId = authenticated || "anyone";
    var local_listen_data = store.get("listen_data");
    if (!local_listen_data) {
        local_listen_data = new Object()
    }
    var user_obj = local_listen_data[userId];
    if (!user_obj) {
        local_listen_data[userId] = new Object()
    }
    function set_listen_time(time) {
        var listen_time = local_listen_data[userId]["value"];
        if (!listen_time) {
            listen_time = 0
        }
        local_listen_data[userId]["value"] = listen_time + time;
        store.set("listen_data", local_listen_data)
    }

    $(function () {
        var server_time = PubFuncs.getServerTime();
        if (JSON.stringify(local_listen_data[userId]) != "{}") {
            if (local_listen_data[userId]["server_date"] != server_time) {
                var get_listen_time = local_listen_data[userId]["value"];
                var set_value = Math.round(get_listen_time / 1000);
                PubFuncs.statistics_duration("listen", set_value, function (data) {
                    if (data.success) {
                        local_listen_data[userId]["server_date"] = server_time;
                        local_listen_data[userId]["value"] = 0;
                        store.set("listen_data", local_listen_data)
                    }
                })
            }
        } else {
            local_listen_data[userId]["server_date"] = server_time;
            local_listen_data[userId]["value"] = 0;
            store.set("listen_data", local_listen_data)
        }
    });
    (function ($) {
        var has3d, hasRot, vendor = "", version = "4.1.0", PI = Math.PI, A90 = PI / 2,
            isTouch = "ontouchstart" in window, mouseEvents = (isTouch) ? {
                down: "touchstart",
                move: "touchmove",
                up: "touchend",
                over: "touchstart",
                out: "touchend"
            } : {down: "mousedown", move: "mousemove", up: "mouseup", over: "mouseover", out: "mouseout"},
            corners = {backward: ["l"], forward: ["r"], all: ["l", "r"]}, displays = ["single", "double"],
            directions = ["ltr", "rtl"], turnOptions = {
                acceleration: true,
                display: "double",
                duration: 600,
                page: 1,
                gradients: true,
                turnCorners: "bl,br",
                when: null,
                ashadow: "white"
            }, flipOptions = {cornerSize: 100}, pagesInDOM = 6, turnMethods = {
                init: function (options) {
                    has3d = "WebKitCSSMatrix" in window || "MozPerspective" in document.body.style;
                    hasRot = rotationAvailable();
                    vendor = getPrefix();
                    var i, that = this, pageNum = 0, data = this.data(), ch = this.children();
                    options = $.extend({
                        width: this.width(),
                        height: this.height(),
                        direction: this.attr("dir") || this.css("direction") || "ltr"
                    }, turnOptions, options);
                    data.opts = options;
                    data.pageObjs = {};
                    data.pages = {};
                    data.pageWrap = {};
                    data.pageZoom = {};
                    data.pagePlace = {};
                    data.pageMv = [];
                    data.zoom = 1;
                    data.totalPages = options.pages || 0;
                    data.eventHandlers = {
                        touchStart: $.proxy(turnMethods._touchStart, this),
                        touchMove: $.proxy(turnMethods._touchMove, this),
                        touchEnd: $.proxy(turnMethods._touchEnd, this),
                        start: $.proxy(turnMethods._eventStart, this)
                    };
                    if (options.when) {
                        for (i in options.when) {
                            if (has(i, options.when)) {
                                this.bind(i, options.when[i])
                            }
                        }
                    }
                    this.css({position: "relative", width: options.width, height: options.height});
                    this.turn("display", options.display);
                    if (options.direction !== "") {
                        this.turn("direction", options.direction)
                    }
                    if (has3d && !isTouch && options.acceleration) {
                        this.transform(translate(0, 0, true))
                    }
                    for (i = 0; i < ch.length; i++) {
                        if ($(ch[i]).attr("ignore") != "1") {
                            this.turn("addPage", ch[i], ++pageNum)
                        }
                    }
                    $(this).bind(mouseEvents.down, data.eventHandlers.touchStart).bind("end", turnMethods._eventEnd).bind("pressed", turnMethods._eventPressed).bind("released", turnMethods._eventReleased).bind("flip", turnMethods._flip);
                    $(this).parent().bind("start", data.eventHandlers.start);
                    $(document).bind(mouseEvents.move, data.eventHandlers.touchMove).bind(mouseEvents.up, data.eventHandlers.touchEnd);
                    this.turn("page", options.page);
                    data.done = true;
                    return this
                }, addPage: function (element, page) {
                    var currentPage, className, incPages = false, data = this.data(), lastPage = data.totalPages + 1;
                    if (data.destroying) {
                        return false
                    }
                    if ((currentPage = /\bp([0-9]+)\b/.exec($(element).attr("class")))) {
                        page = parseInt(currentPage[1], 10)
                    }
                    if (page) {
                        if (page == lastPage) {
                            incPages = true
                        } else {
                            if (page > lastPage) {
                                throw turnError('Page "' + page + '" cannot be inserted')
                            }
                        }
                    } else {
                        page = lastPage;
                        incPages = true
                    }
                    if (page >= 1 && page <= lastPage) {
                        if (data.display == "double") {
                            className = (page % 2) ? " odd" : " even"
                        } else {
                            className = ""
                        }
                        if (data.done) {
                            this.turn("stop")
                        }
                        if (page in data.pageObjs) {
                            turnMethods._movePages.call(this, page, 1)
                        }
                        if (incPages) {
                            data.totalPages = lastPage
                        }
                        data.pageObjs[page] = $(element).css({"float": "left"}).addClass("page p" + page + className);
                        if (!hasHardPage() && data.pageObjs[page].hasClass("hard")) {
                            data.pageObjs[page].removeClass("hard")
                        }
                        turnMethods._addPage.call(this, page);
                        turnMethods._removeFromDOM.call(this)
                    }
                    return this
                }, _addPage: function (page) {
                    var data = this.data(), element = data.pageObjs[page];
                    if (element) {
                        if (turnMethods._necessPage.call(this, page)) {
                            if (!data.pageWrap[page]) {
                                data.pageWrap[page] = $("<div/>", {
                                    "class": "page-wrapper",
                                    page: page,
                                    css: {position: "absolute", overflow: "hidden"}
                                });
                                this.append(data.pageWrap[page]);
                                if (!data.pagePlace[page]) {
                                    data.pagePlace[page] = page;
                                    data.pageObjs[page].appendTo(data.pageWrap[page])
                                }
                                var prop = turnMethods._pageSize.call(this, page, true);
                                element.css({width: prop.width, height: prop.height});
                                data.pageWrap[page].css(prop)
                            }
                            if (data.pagePlace[page] == page) {
                                turnMethods._makeFlip.call(this, page)
                            }
                        } else {
                            data.pagePlace[page] = 0;
                            if (data.pageObjs[page]) {
                                data.pageObjs[page].remove()
                            }
                        }
                    }
                }, hasPage: function (page) {
                    return has(page, this.data().pageObjs)
                }, center: function (page) {
                    var data = this.data(), size = $(this).turn("size"), left = 0;
                    if (!data.noCenter) {
                        if (data.display == "double") {
                            var view = this.turn("view", page || data.tpage || data.page);
                            if (data.direction == "ltr") {
                                if (!view[0]) {
                                    left -= size.width / 4
                                } else {
                                    if (!view[1]) {
                                        left += size.width / 4
                                    }
                                }
                            } else {
                                if (!view[0]) {
                                    left += size.width / 4
                                } else {
                                    if (!view[1]) {
                                        left -= size.width / 4
                                    }
                                }
                            }
                        }
                        $(this).css({marginLeft: left})
                    }
                    return this
                }, destroy: function () {
                    var page, flipbook = this, data = this.data(),
                        events = ["end", "first", "flip", "last", "pressed", "released", "start", "turning", "turned", "zooming", "missing"];
                    if (trigger("destroying", this) == "prevented") {
                        return
                    }
                    data.destroying = true;
                    $.each(events, function (index, eventName) {
                        flipbook.unbind(eventName)
                    });
                    this.parent().unbind("start", data.eventHandlers.start);
                    $(document).unbind(mouseEvents.move, data.eventHandlers.touchMove).unbind(mouseEvents.up, data.eventHandlers.touchEnd);
                    while (data.totalPages !== 0) {
                        this.turn("removePage", data.totalPages)
                    }
                    if (data.fparent) {
                        data.fparent.remove()
                    }
                    if (data.shadow) {
                        data.shadow.remove()
                    }
                    this.removeData();
                    data = null;
                    return this
                }, is: function () {
                    return typeof(this.data().pages) == "object"
                }, zoom: function (newZoom) {
                    var data = this.data();
                    if (typeof(newZoom) == "number") {
                        if (newZoom < 0.001 || newZoom > 100) {
                            throw turnError(newZoom + " is not a value for zoom")
                        }
                        if (trigger("zooming", this, [newZoom, data.zoom]) == "prevented") {
                            return this
                        }
                        var size = this.turn("size"), currentView = this.turn("view"), iz = 1 / data.zoom,
                            newWidth = Math.round(size.width * iz * newZoom),
                            newHeight = Math.round(size.height * iz * newZoom);
                        data.zoom = newZoom;
                        $(this).turn("stop").turn("size", newWidth, newHeight);
                        if (data.opts.autoCenter) {
                            this.turn("center")
                        }
                        turnMethods._updateShadow.call(this);
                        for (var i = 0; i < currentView.length; i++) {
                            if (currentView[i] && data.pageZoom[currentView[i]] != data.zoom) {
                                this.trigger("zoomed", [currentView[i], currentView, data.pageZoom[currentView[i]], data.zoom]);
                                data.pageZoom[currentView[i]] = data.zoom
                            }
                        }
                        return this
                    } else {
                        return data.zoom
                    }
                }, _pageSize: function (page, position) {
                    var data = this.data(), prop = {};
                    if (data.display == "single") {
                        prop.width = this.width();
                        prop.height = this.height();
                        if (position) {
                            prop.top = 0;
                            prop.left = 0;
                            prop.right = "auto"
                        }
                    } else {
                        var pageWidth = this.width() / 2, pageHeight = this.height();
                        if (data.pageObjs[page].hasClass("own-size")) {
                            prop.width = data.pageObjs[page].width();
                            prop.height = data.pageObjs[page].height()
                        } else {
                            prop.width = pageWidth;
                            prop.height = pageHeight
                        }
                        if (position) {
                            var odd = page % 2;
                            prop.top = (pageHeight - prop.height) / 2;
                            if (data.direction == "ltr") {
                                prop[(odd) ? "right" : "left"] = pageWidth - prop.width;
                                prop[(odd) ? "left" : "right"] = "auto"
                            } else {
                                prop[(odd) ? "left" : "right"] = pageWidth - prop.width;
                                prop[(odd) ? "right" : "left"] = "auto"
                            }
                        }
                    }
                    return prop
                }, _makeFlip: function (page) {
                    var data = this.data();
                    if (!data.pages[page] && data.pagePlace[page] == page) {
                        var single = data.display == "single", odd = page % 2;
                        data.pages[page] = data.pageObjs[page].css(turnMethods._pageSize.call(this, page)).flip({
                            page: page,
                            next: (odd || single) ? page + 1 : page - 1,
                            turn: this
                        }).flip("disable", data.disabled);
                        turnMethods._setPageLoc.call(this, page);
                        data.pageZoom[page] = data.zoom
                    }
                    return data.pages[page]
                }, _makeRange: function () {
                    var page, range, data = this.data();
                    if (data.totalPages < 1) {
                        return
                    }
                    range = this.turn("range");
                    for (page = range[0]; page <= range[1]; page++) {
                        turnMethods._addPage.call(this, page)
                    }
                }, range: function (page) {
                    var remainingPages, left, right, view, data = this.data();
                    page = page || data.tpage || data.page || 1;
                    view = turnMethods._view.call(this, page);
                    if (page < 1 || page > data.totalPages) {
                        throw turnError('"' + page + '" is not a valid page')
                    }
                    view[1] = view[1] || view[0];
                    if (view[0] >= 1 && view[1] <= data.totalPages) {
                        remainingPages = Math.floor((pagesInDOM - 2) / 2);
                        if (data.totalPages - view[1] > view[0]) {
                            left = Math.min(view[0] - 1, remainingPages);
                            right = 2 * remainingPages - left
                        } else {
                            right = Math.min(data.totalPages - view[1], remainingPages);
                            left = 2 * remainingPages - right
                        }
                    } else {
                        left = pagesInDOM - 1;
                        right = pagesInDOM - 1
                    }
                    return [Math.max(1, view[0] - left), Math.min(data.totalPages, view[1] + right)]
                }, _necessPage: function (page) {
                    if (page === 0) {
                        return true
                    }
                    var range = this.turn("range");
                    return this.data().pageObjs[page].hasClass("fixed") || (page >= range[0] && page <= range[1])
                }, _removeFromDOM: function () {
                    var page, data = this.data();
                    for (page in data.pageWrap) {
                        if (has(page, data.pageWrap) && !turnMethods._necessPage.call(this, page)) {
                            turnMethods._removePageFromDOM.call(this, page)
                        }
                    }
                }, _removePageFromDOM: function (page) {
                    var data = this.data();
                    if (data.pages[page]) {
                        var dd = data.pages[page].data();
                        flipMethods._moveFoldingPage.call(data.pages[page], false);
                        if (dd.f && dd.f.fwrapper) {
                            dd.f.fwrapper.remove()
                        }
                        data.pages[page].removeData();
                        data.pages[page].remove();
                        delete data.pages[page]
                    }
                    if (data.pageObjs[page]) {
                        data.pageObjs[page].remove()
                    }
                    if (data.pageWrap[page]) {
                        data.pageWrap[page].remove();
                        delete data.pageWrap[page]
                    }
                    turnMethods._removeMv.call(this, page);
                    delete data.pagePlace[page];
                    delete data.pageZoom[page]
                }, removePage: function (page) {
                    var data = this.data();
                    if (page == "*") {
                        while (data.totalPages !== 0) {
                            this.turn("removePage", data.totalPages)
                        }
                    } else {
                        if (page < 1 || page > data.totalPages) {
                            throw turnError("The page " + page + " doesn't exist")
                        }
                        if (data.pageObjs[page]) {
                            this.turn("stop");
                            turnMethods._removePageFromDOM.call(this, page);
                            delete data.pageObjs[page]
                        }
                        turnMethods._movePages.call(this, page, -1);
                        data.totalPages = data.totalPages - 1;
                        if (data.page > data.totalPages) {
                            data.page = null;
                            turnMethods._fitPage.call(this, data.totalPages)
                        } else {
                            turnMethods._makeRange.call(this);
                            this.turn("update")
                        }
                    }
                    return this
                }, _movePages: function (from, change) {
                    var page, that = this, data = this.data(), single = data.display == "single", move = function (page) {
                        var next = page + change, odd = next % 2, className = (odd) ? " odd " : " even ";
                        if (data.pageObjs[page]) {
                            data.pageObjs[next] = data.pageObjs[page].removeClass("p" + page + " odd even").addClass("p" + next + className)
                        }
                        if (data.pagePlace[page] && data.pageWrap[page]) {
                            data.pagePlace[next] = next;
                            if (data.pageObjs[next].hasClass("fixed")) {
                                data.pageWrap[next] = data.pageWrap[page].attr("page", next)
                            } else {
                                data.pageWrap[next] = data.pageWrap[page].css(turnMethods._pageSize.call(that, next, true)).attr("page", next)
                            }
                            if (data.pages[page]) {
                                data.pages[next] = data.pages[page].flip("options", {
                                    page: next,
                                    next: (single || odd) ? next + 1 : next - 1
                                })
                            }
                            if (change) {
                                delete data.pages[page];
                                delete data.pagePlace[page];
                                delete data.pageZoom[page];
                                delete data.pageObjs[page];
                                delete data.pageWrap[page]
                            }
                        }
                    };
                    if (change > 0) {
                        for (page = data.totalPages; page >= from; page--) {
                            move(page)
                        }
                    } else {
                        for (page = from; page <= data.totalPages; page++) {
                            move(page)
                        }
                    }
                }, display: function (display) {
                    var data = this.data(), currentDisplay = data.display;
                    if (display === undefined) {
                        return currentDisplay
                    } else {
                        if ($.inArray(display, displays) == -1) {
                            throw turnError('"' + display + '" is not a value for display')
                        }
                        switch (display) {
                            case"single":
                                if (!data.pageObjs[0]) {
                                    this.turn("stop").css({overflow: "hidden"});
                                    data.pageObjs[0] = $("<div />", {"class": "page p-temporal"}).css({
                                        width: this.width(),
                                        height: this.height()
                                    }).appendTo(this)
                                }
                                this.addClass("shadow");
                                break;
                            case"double":
                                if (data.pageObjs[0]) {
                                    this.turn("stop").css({overflow: ""});
                                    data.pageObjs[0].remove();
                                    delete data.pageObjs[0]
                                }
                                this.removeClass("shadow");
                                break
                        }
                        data.display = display;
                        if (currentDisplay) {
                            var size = this.turn("size");
                            turnMethods._movePages.call(this, 1, 0);
                            this.turn("size", size.width, size.height).turn("update")
                        }
                        return this
                    }
                }, direction: function (dir) {
                    var data = this.data();
                    if (dir === undefined) {
                        return data.direction
                    } else {
                        dir = dir.toLowerCase();
                        if ($.inArray(dir, directions) == -1) {
                            throw turnError('"' + dir + '" is not a value for direction')
                        }
                        if (dir == "rtl") {
                            $(this).attr("dir", "ltr").css({direction: "ltr"})
                        }
                        data.direction = dir;
                        if (data.done) {
                            this.turn("size", $(this).width(), $(this).height())
                        }
                        return this
                    }
                }, animating: function () {
                    return this.data().pageMv.length > 0
                }, corner: function () {
                    var corner, page, data = this.data();
                    for (page in data.pages) {
                        if (has(page, data.pages)) {
                            if ((corner = data.pages[page].flip("corner"))) {
                                return corner
                            }
                        }
                    }
                    return false
                }, data: function () {
                    return this.data()
                }, disable: function (disable) {
                    var page, data = this.data(), view = this.turn("view");
                    data.disabled = disable === undefined || disable === true;
                    for (page in data.pages) {
                        if (has(page, data.pages)) {
                            data.pages[page].flip("disable", (data.disabled) ? true : $.inArray(parseInt(page, 10), view) == -1)
                        }
                    }
                    return this
                }, disabled: function (disable) {
                    if (disable === undefined) {
                        return this.data().disabled === true
                    } else {
                        return this.turn("disable", disable)
                    }
                }, size: function (width, height) {
                    if (width === undefined || height === undefined) {
                        return {width: this.width(), height: this.height()}
                    } else {
                        this.turn("stop");
                        var page, prop, data = this.data(), pageWidth = (data.display == "double") ? width / 2 : width;
                        this.css({width: width, height: height});
                        if (data.pageObjs[0]) {
                            data.pageObjs[0].css({width: pageWidth, height: height})
                        }
                        for (page in data.pageWrap) {
                            if (!has(page, data.pageWrap)) {
                                continue
                            }
                            prop = turnMethods._pageSize.call(this, page, true);
                            data.pageObjs[page].css({width: prop.width, height: prop.height});
                            data.pageWrap[page].css(prop);
                            if (data.pages[page]) {
                                data.pages[page].css({width: prop.width, height: prop.height})
                            }
                        }
                        this.turn("resize");
                        return this
                    }
                }, resize: function () {
                    var page, data = this.data();
                    if (data.pages[0]) {
                        data.pageWrap[0].css({left: -this.width()});
                        data.pages[0].flip("resize", true)
                    }
                    for (page = 1; page <= data.totalPages; page++) {
                        if (data.pages[page]) {
                            data.pages[page].flip("resize", true)
                        }
                    }
                    turnMethods._updateShadow.call(this);
                    if (data.opts.autoCenter) {
                        this.turn("center")
                    }
                }, _removeMv: function (page) {
                    var i, data = this.data();
                    for (i = 0; i < data.pageMv.length; i++) {
                        if (data.pageMv[i] == page) {
                            data.pageMv.splice(i, 1);
                            return true
                        }
                    }
                    return false
                }, _addMv: function (page) {
                    var data = this.data();
                    turnMethods._removeMv.call(this, page);
                    data.pageMv.push(page)
                }, _view: function (page) {
                    var data = this.data();
                    page = page || data.page;
                    if (data.display == "double") {
                        return (page % 2) ? [page - 1, page] : [page, page + 1]
                    } else {
                        return [page]
                    }
                }, view: function (page) {
                    var data = this.data(), view = turnMethods._view.call(this, page);
                    if (data.display == "double") {
                        return [(view[0] > 0) ? view[0] : 0, (view[1] <= data.totalPages) ? view[1] : 0]
                    } else {
                        return [(view[0] > 0 && view[0] <= data.totalPages) ? view[0] : 0]
                    }
                }, stop: function (ignore, animate) {
                    if (this.turn("animating")) {
                        var i, opts, page, data = this.data();
                        if (data.tpage) {
                            data.page = data.tpage;
                            delete data.tpage
                        }
                        for (i = 0; i < data.pageMv.length; i++) {
                            if (!data.pageMv[i] || data.pageMv[i] === ignore) {
                                continue
                            }
                            page = data.pages[data.pageMv[i]];
                            opts = page.data().f.opts;
                            page.flip("hideFoldedPage", animate);
                            if (!animate) {
                                flipMethods._moveFoldingPage.call(page, false)
                            }
                            if (opts.force) {
                                opts.next = (opts.page % 2 === 0) ? opts.page - 1 : opts.page + 1;
                                delete opts.force
                            }
                        }
                    }
                    this.turn("update");
                    return this
                }, pages: function (pages) {
                    var data = this.data();
                    if (pages) {
                        if (pages < data.totalPages) {
                            for (var page = data.totalPages; page > pages; page--) {
                                this.turn("removePage", page)
                            }
                        }
                        data.totalPages = pages;
                        turnMethods._fitPage.call(this, data.page);
                        return this
                    } else {
                        return data.totalPages
                    }
                }, _missing: function (page) {
                    var data = this.data();
                    if (data.totalPages < 1) {
                        return
                    }
                    var p, range = this.turn("range", page), missing = [];
                    for (p = range[0]; p <= range[1]; p++) {
                        if (!data.pageObjs[p]) {
                            missing.push(p)
                        }
                    }
                    if (missing.length > 0) {
                        this.trigger("missing", [missing])
                    }
                }, _fitPage: function (page) {
                    var data = this.data(), newView = this.turn("view", page);
                    turnMethods._missing.call(this, page);
                    if (!data.pageObjs[page]) {
                        return
                    }
                    data.page = page;
                    this.turn("stop");
                    for (var i = 0; i < newView.length; i++) {
                        if (newView[i] && data.pageZoom[newView[i]] != data.zoom) {
                            this.trigger("zoomed", [newView[i], newView, data.pageZoom[newView[i]], data.zoom]);
                            data.pageZoom[newView[i]] = data.zoom
                        }
                    }
                    turnMethods._removeFromDOM.call(this);
                    turnMethods._makeRange.call(this);
                    turnMethods._updateShadow.call(this);
                    this.trigger("turned", [page, newView]);
                    this.turn("update");
                    if (data.opts.autoCenter) {
                        this.turn("center")
                    }
                }, _turnPage: function (page) {
                    var current, next, data = this.data(), place = data.pagePlace[page], view = this.turn("view"),
                        newView = this.turn("view", page);
                    if (data.page != page) {
                        var currentPage = data.page;
                        if (trigger("turning", this, [page, newView]) == "prevented") {
                            if (currentPage == data.page && $.inArray(place, data.pageMv) != -1) {
                                data.pages[place].flip("hideFoldedPage", true)
                            }
                            return
                        }
                        if ($.inArray(1, newView) != -1) {
                            this.trigger("first")
                        }
                        if ($.inArray(data.totalPages, newView) != -1) {
                            this.trigger("last")
                        }
                    }
                    if (data.display == "single") {
                        current = view[0];
                        next = newView[0]
                    } else {
                        if (view[1] && page > view[1]) {
                            current = view[1];
                            next = newView[0]
                        } else {
                            if (view[0] && page < view[0]) {
                                current = view[0];
                                next = newView[1]
                            }
                        }
                    }
                    var optsCorners = data.opts.turnCorners.split(","), flipData = data.pages[current].data().f,
                        opts = flipData.opts, actualPoint = flipData.point;
                    turnMethods._missing.call(this, page);
                    if (!data.pageObjs[page]) {
                        return
                    }
                    this.turn("stop");
                    data.page = page;
                    turnMethods._makeRange.call(this);
                    data.tpage = next;
                    if (opts.next != next) {
                        opts.next = next;
                        opts.force = true
                    }
                    this.turn("update");
                    flipData.point = actualPoint;
                    if (flipData.effect == "hard") {
                        if (data.direction == "ltr") {
                            data.pages[current].flip("turnPage", (page > current) ? "r" : "l")
                        } else {
                            data.pages[current].flip("turnPage", (page > current) ? "l" : "r")
                        }
                    } else {
                        if (data.direction == "ltr") {
                            data.pages[current].flip("turnPage", optsCorners[(page > current) ? 1 : 0])
                        } else {
                            data.pages[current].flip("turnPage", optsCorners[(page > current) ? 0 : 1])
                        }
                    }
                }, page: function (page) {
                    var data = this.data();
                    if (page === undefined) {
                        return data.page
                    } else {
                        if (!data.disabled && !data.destroying) {
                            page = parseInt(page, 10);
                            if (page > 0 && page <= data.totalPages) {
                                if (page != data.page) {
                                    if (!data.done || $.inArray(page, this.turn("view")) != -1) {
                                        turnMethods._fitPage.call(this, page)
                                    } else {
                                        turnMethods._turnPage.call(this, page)
                                    }
                                }
                                return this
                            } else {
                                throw turnError("The page " + page + " does not exist")
                            }
                        }
                    }
                }, next: function () {
                    return this.turn("page", Math.min(this.data().totalPages, turnMethods._view.call(this, this.data().page).pop() + 1))
                }, previous: function () {
                    return this.turn("page", Math.max(1, turnMethods._view.call(this, this.data().page).shift() - 1))
                }, peel: function (corner, animate) {
                    var data = this.data(), view = this.turn("view");
                    animate = (animate === undefined) ? true : animate === true;
                    if (corner === false) {
                        this.turn("stop", null, animate)
                    } else {
                        if (data.display == "single") {
                            data.pages[data.page].flip("peel", corner, animate)
                        } else {
                            var page;
                            if (data.direction == "ltr") {
                                page = (corner.indexOf("l") != -1) ? view[0] : view[1]
                            } else {
                                page = (corner.indexOf("l") != -1) ? view[1] : view[0]
                            }
                            if (data.pages[page]) {
                                data.pages[page].flip("peel", corner, animate)
                            }
                        }
                    }
                    return this
                }, _addMotionPage: function () {
                    var opts = $(this).data().f.opts, turn = opts.turn, dd = turn.data();
                    turnMethods._addMv.call(turn, opts.page)
                }, _eventStart: function (e, opts, corner) {
                    var data = opts.turn.data(), actualZoom = data.pageZoom[opts.page];
                    if (e.isDefaultPrevented()) {
                        turnMethods._updateShadow.call(opts.turn);
                        return
                    }
                    if (actualZoom && actualZoom != data.zoom) {
                        opts.turn.trigger("zoomed", [opts.page, opts.turn.turn("view", opts.page), actualZoom, data.zoom]);
                        data.pageZoom[opts.page] = data.zoom
                    }
                    if (data.display == "single" && corner) {
                        if ((corner.charAt(1) == "l" && data.direction == "ltr") || (corner.charAt(1) == "r" && data.direction == "rtl")) {
                            opts.next = (opts.next < opts.page) ? opts.next : opts.page - 1;
                            opts.force = true
                        } else {
                            opts.next = (opts.next > opts.page) ? opts.next : opts.page + 1
                        }
                    }
                    turnMethods._addMotionPage.call(e.target);
                    turnMethods._updateShadow.call(opts.turn)
                }, _eventEnd: function (e, opts, turned) {
                    var that = $(e.target), data = that.data().f, turn = opts.turn, dd = turn.data();
                    if (turned) {
                        var tpage = dd.tpage || dd.page;
                        if (tpage == opts.next || tpage == opts.page) {
                            delete dd.tpage;
                            turnMethods._fitPage.call(turn, tpage || opts.next, true)
                        }
                    } else {
                        turnMethods._removeMv.call(turn, opts.page);
                        turnMethods._updateShadow.call(turn);
                        turn.turn("update")
                    }
                }, _eventPressed: function (e) {
                    var page, data = $(e.target).data().f, turn = data.opts.turn, turnData = turn.data(),
                        pages = turnData.pages;
                    turnData.mouseAction = true;
                    turn.turn("update");
                    return data.time = new Date().getTime()
                }, _eventReleased: function (e, point) {
                    var outArea, page = $(e.target), data = page.data().f, turn = data.opts.turn, turnData = turn.data();
                    if (turnData.display == "single") {
                        outArea = (point.corner == "br" || point.corner == "tr") ? point.x < page.width() / 2 : point.x > page.width() / 2
                    } else {
                        outArea = point.x < 0 || point.x > page.width()
                    }
                    if ((new Date()).getTime() - data.time < 200 || outArea) {
                        e.preventDefault();
                        turnMethods._turnPage.call(turn, data.opts.next)
                    }
                    turnData.mouseAction = false
                }, _flip: function (e) {
                    e.stopPropagation();
                    var opts = $(e.target).data().f.opts;
                    opts.turn.trigger("turn", [opts.next]);
                    if (opts.turn.data().opts.autoCenter) {
                        opts.turn.turn("center", opts.next)
                    }
                }, _touchStart: function () {
                    var data = this.data();
                    for (var page in data.pages) {
                        if (has(page, data.pages) && flipMethods._eventStart.apply(data.pages[page], arguments) === false) {
                            return false
                        }
                    }
                }, _touchMove: function () {
                    var data = this.data();
                    for (var page in data.pages) {
                        if (has(page, data.pages)) {
                            flipMethods._eventMove.apply(data.pages[page], arguments)
                        }
                    }
                }, _touchEnd: function () {
                    console.log("_touchEnd");
                    var data = this.data();
                    for (var page in data.pages) {
                        if (has(page, data.pages)) {
                            flipMethods._eventEnd.apply(data.pages[page], arguments)
                        }
                    }
                }, calculateZ: function (mv) {
                    var i, page, nextPage, placePage, dpage, that = this, data = this.data(), view = this.turn("view"),
                        currentPage = view[0] || view[1], total = mv.length - 1, r = {pageZ: {}, partZ: {}, pageV: {}},
                        addView = function (page) {
                            var view = that.turn("view", page);
                            if (view[0]) {
                                r.pageV[view[0]] = true
                            }
                            if (view[1]) {
                                r.pageV[view[1]] = true
                            }
                        };
                    for (i = 0; i <= total; i++) {
                        page = mv[i];
                        nextPage = data.pages[page].data().f.opts.next;
                        placePage = data.pagePlace[page];
                        addView(page);
                        addView(nextPage);
                        dpage = (data.pagePlace[nextPage] == nextPage) ? nextPage : page;
                        r.pageZ[dpage] = data.totalPages - Math.abs(currentPage - dpage);
                        r.partZ[placePage] = data.totalPages * 2 - total + i
                    }
                    return r
                }, update: function () {
                    var page, data = this.data();
                    if (this.turn("animating") && data.pageMv[0] !== 0) {
                        var p, apage, fixed, pos = this.turn("calculateZ", data.pageMv), corner = this.turn("corner"),
                            actualView = this.turn("view"), newView = this.turn("view", data.tpage);
                        for (page in data.pageWrap) {
                            if (!has(page, data.pageWrap)) {
                                continue
                            }
                            fixed = data.pageObjs[page].hasClass("fixed");
                            data.pageWrap[page].css({
                                display: (pos.pageV[page] || fixed) ? "" : "none",
                                zIndex: (data.pageObjs[page].hasClass("hard") ? pos.partZ[page] : pos.pageZ[page]) || (fixed ? -1 : 0)
                            });
                            if ((p = data.pages[page])) {
                                p.flip("z", pos.partZ[page] || null);
                                if (pos.pageV[page]) {
                                    p.flip("resize")
                                }
                                if (data.tpage) {
                                    p.flip("hover", false).flip("disable", $.inArray(parseInt(page, 10), data.pageMv) == -1 && page != newView[0] && page != newView[1])
                                } else {
                                    p.flip("hover", corner === false).flip("disable", page != actualView[0] && page != actualView[1])
                                }
                            }
                        }
                    } else {
                        for (page in data.pageWrap) {
                            if (!has(page, data.pageWrap)) {
                                continue
                            }
                            var pageLocation = turnMethods._setPageLoc.call(this, page);
                            if (data.pages[page]) {
                                data.pages[page].flip("disable", data.disabled || pageLocation != 1).flip("hover", true).flip("z", null)
                            }
                        }
                    }
                    return this
                }, _updateShadow: function () {
                    var view, view2, shadow, data = this.data(), width = this.width(), height = this.height(),
                        pageWidth = (data.display == "single") ? width : width / 2;
                    view = this.turn("view");
                    if (!data.shadow) {
                        data.shadow = $("<div />", {"class": "shadow", css: divAtt(0, 0, 0).css}).appendTo(this)
                    }
                    for (var i = 0; i < data.pageMv.length; i++) {
                        if (!view[0] || !view[1]) {
                            break
                        }
                        view = this.turn("view", data.pages[data.pageMv[i]].data().f.opts.next);
                        view2 = this.turn("view", data.pageMv[i]);
                        view[0] = view[0] && view2[0];
                        view[1] = view[1] && view2[1]
                    }
                    if (!view[0]) {
                        shadow = (data.direction == "ltr") ? 1 : 2
                    } else {
                        if (!view[1]) {
                            shadow = (data.direction == "ltr") ? 2 : 1
                        } else {
                            shadow = 3
                        }
                    }
                    switch (shadow) {
                        case 1:
                            data.shadow.css({width: pageWidth, height: height, top: 0, left: pageWidth});
                            break;
                        case 2:
                            data.shadow.css({width: pageWidth, height: height, top: 0, left: 0});
                            break;
                        case 3:
                            data.shadow.css({width: width, height: height, top: 0, left: 0});
                            break
                    }
                }, _setPageLoc: function (page) {
                    var data = this.data(), view = this.turn("view"), loc = 0;
                    if (page == view[0] || page == view[1]) {
                        loc = 1
                    } else {
                        if ((data.display == "single" && page == view[0] + 1) || (data.display == "double" && page == view[0] - 2 || page == view[1] + 2)) {
                            loc = 2
                        }
                    }
                    if (!this.turn("animating")) {
                        switch (loc) {
                            case 1:
                                data.pageWrap[page].css({zIndex: data.totalPages, display: ""});
                                break;
                            case 2:
                                data.pageWrap[page].css({zIndex: data.totalPages - 1, display: ""});
                                break;
                            case 0:
                                data.pageWrap[page].css({
                                    zIndex: 0,
                                    display: (data.pageObjs[page].hasClass("fixed")) ? "" : "none"
                                });
                                break
                        }
                    }
                    return loc
                }, options: function (options) {
                    if (options === undefined) {
                        return this.data().opts
                    } else {
                        var data = this.data();
                        $.extend(data.opts, options);
                        if (options.pages) {
                            this.turn("pages", options.pages)
                        }
                        if (options.page) {
                            this.turn("page", options.page)
                        }
                        if (options.display) {
                            this.turn("display", options.display)
                        }
                        if (options.direction) {
                            this.turn("direction", options.direction)
                        }
                        if (options.width && options.height) {
                            this.turn("size", options.width, options.height)
                        }
                        if (options.when) {
                            for (var eventName in options.when) {
                                if (has(eventName, options.when)) {
                                    this.unbind(eventName).bind(eventName, options.when[eventName])
                                }
                            }
                        }
                        return this
                    }
                }, version: function () {
                    return version
                }
            }, flipMethods = {
                init: function (opts) {
                    this.data({f: {disabled: false, hover: false, effect: (this.hasClass("hard")) ? "hard" : "sheet"}});
                    this.flip("options", opts);
                    flipMethods._addPageWrapper.call(this);
                    return this
                }, setData: function (d) {
                    var data = this.data();
                    data.f = $.extend(data.f, d);
                    return this
                }, options: function (opts) {
                    var data = this.data().f;
                    if (opts) {
                        flipMethods.setData.call(this, {opts: $.extend({}, data.opts || flipOptions, opts)});
                        return this
                    } else {
                        return data.opts
                    }
                }, z: function (z) {
                    var data = this.data().f;
                    data.opts["z-index"] = z;
                    if (data.fwrapper) {
                        data.fwrapper.css({zIndex: z || parseInt(data.parent.css("z-index"), 10) || 0})
                    }
                    return this
                }, _cAllowed: function () {
                    var data = this.data().f, page = data.opts.page, turnData = data.opts.turn.data(), odd = page % 2;
                    if (data.effect == "hard") {
                        return (turnData.direction == "ltr") ? [(odd) ? "r" : "l"] : [(odd) ? "l" : "r"]
                    } else {
                        if (turnData.display == "single") {
                            if (page == 1) {
                                return (turnData.direction == "ltr") ? corners.forward : corners.backward
                            } else {
                                if (page == turnData.totalPages) {
                                    return (turnData.direction == "ltr") ? corners.backward : corners.forward
                                } else {
                                    return corners.all
                                }
                            }
                        } else {
                            return (turnData.direction == "ltr") ? corners[(odd) ? "forward" : "backward"] : corners[(odd) ? "backward" : "forward"]
                        }
                    }
                }, _cornerActivated: function (p) {
                    var data = this.data().f, width = this.width(), height = this.height(),
                        point = {x: p.x, y: p.y, corner: ""}, csz = data.opts.cornerSize;
                    if (point.x <= 0 || point.y <= 0 || point.x >= width || point.y >= height) {
                        return false
                    }
                    var allowedCorners = flipMethods._cAllowed.call(this);
                    switch (data.effect) {
                        case"hard":
                            if (point.x > width - csz) {
                                point.corner = "r"
                            } else {
                                if (point.x < csz) {
                                    point.corner = "l"
                                } else {
                                    return false
                                }
                            }
                            break;
                        case"sheet":
                            if (point.y < csz) {
                                point.corner += "t"
                            } else {
                                if (point.y >= height - csz) {
                                    point.corner += "b"
                                } else {
                                    return false
                                }
                            }
                            if (point.x <= csz) {
                                point.corner += "l"
                            } else {
                                if (point.x >= width - csz) {
                                    point.corner += "r"
                                } else {
                                    return false
                                }
                            }
                            break
                    }
                    return (!point.corner || $.inArray(point.corner, allowedCorners) == -1) ? false : point
                }, _isIArea: function (e) {
                    var pos = this.data().f.parent.offset();
                    e = (isTouch && e.originalEvent) ? e.originalEvent.touches[0] : e;
                    return flipMethods._cornerActivated.call(this, {x: e.pageX - pos.left, y: e.pageY - pos.top})
                }, _c: function (corner, opts) {
                    opts = opts || 0;
                    switch (corner) {
                        case"tl":
                            return point2D(opts, opts);
                        case"tr":
                            return point2D(this.width() - opts, opts);
                        case"bl":
                            return point2D(opts, this.height() - opts);
                        case"br":
                            return point2D(this.width() - opts, this.height() - opts);
                        case"l":
                            return point2D(opts, 0);
                        case"r":
                            return point2D(this.width() - opts, 0)
                    }
                }, _c2: function (corner) {
                    switch (corner) {
                        case"tl":
                            return point2D(this.width() * 2, 0);
                        case"tr":
                            return point2D(-this.width(), 0);
                        case"bl":
                            return point2D(this.width() * 2, this.height());
                        case"br":
                            return point2D(-this.width(), this.height());
                        case"l":
                            return point2D(this.width() * 2, 0);
                        case"r":
                            return point2D(-this.width(), 0)
                    }
                }, _foldingPage: function () {
                    var data = this.data().f;
                    if (!data) {
                        return
                    }
                    var opts = data.opts;
                    if (opts.turn) {
                        data = opts.turn.data();
                        if (data.display == "single") {
                            return (opts.next > 1 || opts.page > 1) ? data.pageObjs[0] : null
                        } else {
                            return data.pageObjs[opts.next]
                        }
                    }
                }, _backGradient: function () {
                    var data = this.data().f, turnData = data.opts.turn.data(),
                        gradient = turnData.opts.gradients && (turnData.display == "single" || (data.opts.page != 2 && data.opts.page != turnData.totalPages - 1));
                    if (gradient && !data.bshadow) {
                        data.bshadow = $("<div/>", divAtt(0, 0, 1)).css({
                            position: "",
                            width: this.width(),
                            height: this.height()
                        }).appendTo(data.parent)
                    }
                    return gradient
                }, type: function () {
                    return this.data().f.effect
                }, resize: function (full) {
                    var data = this.data().f, turnData = data.opts.turn.data(), width = this.width(),
                        height = this.height();
                    switch (data.effect) {
                        case"hard":
                            if (full) {
                                data.wrapper.css({width: width, height: height});
                                data.fpage.css({width: width, height: height});
                                if (turnData.opts.gradients) {
                                    data.ashadow.css({width: width, height: height});
                                    data.bshadow.css({width: width, height: height})
                                }
                            }
                            break;
                        case"sheet":
                            if (full) {
                                var size = Math.round(Math.sqrt(Math.pow(width, 2) + Math.pow(height, 2)));
                                data.wrapper.css({width: size, height: size});
                                data.fwrapper.css({width: size, height: size}).children(":first-child").css({
                                    width: width,
                                    height: height
                                });
                                data.fpage.css({width: width, height: height});
                                if (turnData.opts.gradients) {
                                    data.ashadow.css({width: width, height: height, background: turnData.opts.ashadow})
                                }
                                if (flipMethods._backGradient.call(this)) {
                                    data.bshadow.css({width: width, height: height})
                                }
                            }
                            if (data.parent.is(":visible")) {
                                var offset = findPos(data.parent[0]);
                                data.fwrapper.css({top: offset.top, left: offset.left});
                                offset = findPos(data.opts.turn[0]);
                                data.fparent.css({top: -offset.top, left: -offset.left})
                            }
                            this.flip("z", data.opts["z-index"]);
                            break
                    }
                }, _addPageWrapper: function () {
                    var att, data = this.data().f, turnData = data.opts.turn.data(), parent = this.parent();
                    data.parent = parent;
                    if (!data.wrapper) {
                        switch (data.effect) {
                            case"hard":
                                var cssProperties = {};
                                cssProperties[vendor + "transform-style"] = "preserve-3d";
                                cssProperties[vendor + "backface-visibility"] = "hidden";
                                data.wrapper = $("<div/>", divAtt(0, 0, 2)).css(cssProperties).appendTo(parent).prepend(this);
                                data.fpage = $("<div/>", divAtt(0, 0, 1)).css(cssProperties).appendTo(parent);
                                if (turnData.opts.gradients) {
                                    data.ashadow = $("<div/>", divAtt(0, 0, 0)).hide().appendTo(parent);
                                    data.bshadow = $("<div/>", divAtt(0, 0, 0))
                                }
                                break;
                            case"sheet":
                                var width = this.width(), height = this.height(),
                                    size = Math.round(Math.sqrt(Math.pow(width, 2) + Math.pow(height, 2)));
                                data.fparent = data.opts.turn.data().fparent;
                                if (!data.fparent) {
                                    var fparent = $("<div/>", {css: {"pointer-events": "none"}}).hide();
                                    fparent.data().flips = 0;
                                    fparent.css(divAtt(0, 0, "auto", "visible").css).appendTo(data.opts.turn);
                                    data.opts.turn.data().fparent = fparent;
                                    data.fparent = fparent
                                }
                                this.css({position: "absolute", top: 0, left: 0, bottom: "auto", right: "auto"});
                                data.wrapper = $("<div/>", divAtt(0, 0, this.css("z-index"))).appendTo(parent).prepend(this);
                                data.fwrapper = $("<div/>", divAtt(parent.offset().top, parent.offset().left)).hide().appendTo(data.fparent);
                                data.fpage = $("<div/>", divAtt(0, 0, 0, "visible")).css({cursor: "default"}).appendTo(data.fwrapper);
                                if (turnData.opts.gradients) {
                                    data.ashadow = $("<div/>", divAtt(0, 0, 1)).appendTo(data.fpage)
                                }
                                flipMethods.setData.call(this, data);
                                break
                        }
                    }
                    flipMethods.resize.call(this, true)
                }, _fold: function (point) {
                    var data = this.data().f, turnData = data.opts.turn.data(), o = flipMethods._c.call(this, point.corner),
                        width = this.width(), height = this.height();
                    switch (data.effect) {
                        case"hard":
                            if (point.corner == "l") {
                                point.x = Math.min(Math.max(point.x, 0), width * 2)
                            } else {
                                point.x = Math.max(Math.min(point.x, width), -width)
                            }
                            var leftPos, shadow, gradientX, fpageOrigin, parentOrigin, totalPages = turnData.totalPages,
                                zIndex = data.opts["z-index"] || totalPages, parentCss = {overflow: "visible"},
                                relX = (o.x) ? (o.x - point.x) / width : point.x / width, angle = relX * 90,
                                half = angle < 90;
                            switch (point.corner) {
                                case"l":
                                    fpageOrigin = "0% 50%";
                                    parentOrigin = "100% 50%";
                                    if (half) {
                                        leftPos = 0;
                                        shadow = data.opts.next - 1 > 0;
                                        gradientX = 1
                                    } else {
                                        leftPos = "100%";
                                        shadow = data.opts.page + 1 < totalPages;
                                        gradientX = 0
                                    }
                                    break;
                                case"r":
                                    fpageOrigin = "100% 50%";
                                    parentOrigin = "0% 50%";
                                    angle = -angle;
                                    width = -width;
                                    if (half) {
                                        leftPos = 0;
                                        shadow = data.opts.next + 1 < totalPages;
                                        gradientX = 0
                                    } else {
                                        leftPos = "-100%";
                                        shadow = data.opts.page != 1;
                                        gradientX = 1
                                    }
                                    break
                            }
                            parentCss[vendor + "perspective-origin"] = parentOrigin;
                            data.wrapper.transform("rotateY(" + angle + "deg)translate3d(0px, 0px, " + (this.attr("depth") || 0) + "px)", parentOrigin);
                            data.fpage.transform("translateX(" + width + "px) rotateY(" + (180 + angle) + "deg)", fpageOrigin);
                            data.parent.css(parentCss);
                            if (half) {
                                relX = -relX + 1;
                                data.wrapper.css({zIndex: zIndex + 1});
                                data.fpage.css({zIndex: zIndex})
                            } else {
                                relX = relX - 1;
                                data.wrapper.css({zIndex: zIndex});
                                data.fpage.css({zIndex: zIndex + 1})
                            }
                            if (turnData.opts.gradients) {
                                if (shadow) {
                                    data.ashadow.css({
                                        display: "",
                                        left: leftPos,
                                        backgroundColor: "rgb(255,255,255)"
                                    }).transform("rotateY(0deg)")
                                } else {
                                    data.ashadow.hide()
                                }
                                data.bshadow.css({opacity: -relX + 1});
                                if (half) {
                                    if (data.bshadow.parent()[0] != data.wrapper[0]) {
                                        data.bshadow.appendTo(data.wrapper)
                                    }
                                } else {
                                    if (data.bshadow.parent()[0] != data.fpage[0]) {
                                        data.bshadow.appendTo(data.fpage)
                                    }
                                }
                                gradient(data.bshadow, point2D(gradientX * 100, 0), point2D((-gradientX + 1) * 100, 0), [[0, "rgba(0,0,0,0.3)"], [1, "rgba(0,0,0,0)"]], 2)
                            }
                            break;
                        case"sheet":
                            var that = this, a = 0, alpha = 0, beta, px, gradientEndPointA, gradientEndPointB,
                                gradientStartVal, gradientSize, gradientOpacity, shadowVal, mv = point2D(0, 0),
                                df = point2D(0, 0), tr = point2D(0, 0), folding = flipMethods._foldingPage.call(this),
                                tan = Math.tan(alpha), ac = turnData.opts.acceleration, h = data.wrapper.height(),
                                top = point.corner.substr(0, 1) == "t", left = point.corner.substr(1, 1) == "l",
                                compute = function () {
                                    var rel = point2D(0, 0);
                                    var middle = point2D(0, 0);
                                    rel.x = (o.x) ? o.x - point.x : point.x;
                                    if (!hasRot) {
                                        rel.y = 0
                                    } else {
                                        rel.y = (o.y) ? o.y - point.y : point.y
                                    }
                                    middle.x = (left) ? width - rel.x / 2 : point.x + rel.x / 2;
                                    middle.y = rel.y / 2;
                                    var alpha = A90 - Math.atan2(rel.y, rel.x),
                                        gamma = alpha - Math.atan2(middle.y, middle.x),
                                        distance = Math.max(0, Math.sin(gamma) * Math.sqrt(Math.pow(middle.x, 2) + Math.pow(middle.y, 2)));
                                    a = deg(alpha);
                                    tr = point2D(distance * Math.sin(alpha), distance * Math.cos(alpha));
                                    if (alpha > A90) {
                                        tr.x = tr.x + Math.abs(tr.y * rel.y / rel.x);
                                        tr.y = 0;
                                        if (Math.round(tr.x * Math.tan(PI - alpha)) < height) {
                                            point.y = Math.sqrt(Math.pow(height, 2) + 2 * middle.x * rel.x);
                                            if (top) {
                                                point.y = height - point.y
                                            }
                                            return compute()
                                        }
                                    }
                                    if (alpha > A90) {
                                        var beta = PI - alpha, dd = h - height / Math.sin(beta);
                                        mv = point2D(Math.round(dd * Math.cos(beta)), Math.round(dd * Math.sin(beta)));
                                        if (left) {
                                            mv.x = -mv.x
                                        }
                                        if (top) {
                                            mv.y = -mv.y
                                        }
                                    }
                                    px = Math.round(tr.y / Math.tan(alpha) + tr.x);
                                    var side = width - px, sideX = side * Math.cos(alpha * 2),
                                        sideY = side * Math.sin(alpha * 2);
                                    df = point2D(Math.round((left ? side - sideX : px + sideX)), Math.round((top) ? sideY : height - sideY));
                                    if (turnData.opts.gradients) {
                                        gradientSize = side * Math.sin(alpha);
                                        var endingPoint = flipMethods._c2.call(that, point.corner),
                                            far = Math.sqrt(Math.pow(endingPoint.x - point.x, 2) + Math.pow(endingPoint.y - point.y, 2)) / width;
                                        shadowVal = Math.sin(A90 * ((far > 1) ? 2 - far : far));
                                        gradientOpacity = Math.min(far, 1);
                                        gradientStartVal = gradientSize > 100 ? (gradientSize - 100) / gradientSize : 0;
                                        gradientEndPointA = point2D(gradientSize * Math.sin(alpha) / width * 100, gradientSize * Math.cos(alpha) / height * 100);
                                        if (flipMethods._backGradient.call(that)) {
                                            gradientEndPointB = point2D(gradientSize * 1.2 * Math.sin(alpha) / width * 100, gradientSize * 1.2 * Math.cos(alpha) / height * 100);
                                            if (!left) {
                                                gradientEndPointB.x = 100 - gradientEndPointB.x
                                            }
                                            if (!top) {
                                                gradientEndPointB.y = 100 - gradientEndPointB.y
                                            }
                                        }
                                    }
                                    tr.x = Math.round(tr.x);
                                    tr.y = Math.round(tr.y);
                                    return true
                                }, transform = function (tr, c, x, a) {
                                    var f = ["0", "auto"], mvW = (width - h) * x[0] / 100, mvH = (height - h) * x[1] / 100,
                                        cssA = {left: f[c[0]], top: f[c[1]], right: f[c[2]], bottom: f[c[3]]}, cssB = {},
                                        aliasingFk = (a != 90 && a != -90) ? (left ? -1 : 1) : 0,
                                        origin = x[0] + "% " + x[1] + "%";
                                    that.css(cssA).transform(rotate(a) + translate(tr.x + aliasingFk, tr.y, ac), origin);
                                    data.fpage.css(cssA).transform(rotate(a) + translate(tr.x + df.x - mv.x - width * x[0] / 100, tr.y + df.y - mv.y - height * x[1] / 100, ac) + rotate((180 / a - 2) * a), origin);
                                    data.wrapper.transform(translate(-tr.x + mvW - aliasingFk, -tr.y + mvH, ac) + rotate(-a), origin);
                                    data.fwrapper.transform(translate(-tr.x + mv.x + mvW, -tr.y + mv.y + mvH, ac) + rotate(-a), origin);
                                    if (turnData.opts.gradients) {
                                        if (x[0]) {
                                            gradientEndPointA.x = 100 - gradientEndPointA.x
                                        }
                                        if (x[1]) {
                                            gradientEndPointA.y = (100 - gradientEndPointA.y)
                                        }
                                        cssB["box-shadow"] = "0 0 20px rgba(0,0,0," + (0.5 * shadowVal) + ")";
                                        folding.css(cssB);
                                        gradient(data.ashadow, point2D(left ? 100 : 0, top ? 0 : 100), point2D(gradientEndPointA.x, gradientEndPointA.y), [[gradientStartVal, "rgba(0,0,0,0)"], [((1 - gradientStartVal) * 0.8) + gradientStartVal, "rgba(0,0,0," + (0.2 * gradientOpacity) + ")"], [1, "rgba(255,255,255," + (0.2 * gradientOpacity) + ")"]], 3, alpha);
                                        if (flipMethods._backGradient.call(that)) {
                                            gradient(data.bshadow, point2D(left ? 0 : 100, top ? 0 : 100), point2D(gradientEndPointB.x, gradientEndPointB.y), [[0.6, "rgba(0,0,0,0)"], [0.8, "rgba(0,0,0," + (0.3 * gradientOpacity) + ")"], [1, "rgba(0,0,0,0)"]], 3)
                                        }
                                    }
                                };
                            switch (point.corner) {
                                case"l":
                                    break;
                                case"r":
                                    break;
                                case"tl":
                                    point.x = Math.max(point.x, 1);
                                    compute();
                                    transform(tr, [1, 0, 0, 1], [100, 0], a);
                                    break;
                                case"tr":
                                    point.x = Math.min(point.x, width - 1);
                                    compute();
                                    transform(point2D(-tr.x, tr.y), [0, 0, 0, 1], [0, 0], -a);
                                    break;
                                case"bl":
                                    point.x = Math.max(point.x, 1);
                                    compute();
                                    transform(point2D(tr.x, -tr.y), [1, 1, 0, 0], [100, 100], -a);
                                    break;
                                case"br":
                                    point.x = Math.min(point.x, width - 1);
                                    compute();
                                    transform(point2D(-tr.x, -tr.y), [0, 1, 1, 0], [0, 100], a);
                                    break
                            }
                            break
                    }
                    data.point = point
                }, _moveFoldingPage: function (move) {
                    var data = this.data().f;
                    if (!data) {
                        return
                    }
                    var turn = data.opts.turn, turnData = turn.data(), place = turnData.pagePlace;
                    if (move) {
                        var nextPage = data.opts.next;
                        if (place[nextPage] != data.opts.page) {
                            if (data.folding) {
                                flipMethods._moveFoldingPage.call(this, false)
                            }
                            var folding = flipMethods._foldingPage.call(this);
                            folding.appendTo(data.fpage);
                            place[nextPage] = data.opts.page;
                            data.folding = nextPage
                        }
                        turn.turn("update")
                    } else {
                        if (data.folding) {
                            if (turnData.pages[data.folding]) {
                                var flipData = turnData.pages[data.folding].data().f;
                                turnData.pageObjs[data.folding].appendTo(flipData.wrapper)
                            } else {
                                if (turnData.pageWrap[data.folding]) {
                                    turnData.pageObjs[data.folding].appendTo(turnData.pageWrap[data.folding])
                                }
                            }
                            if (data.folding in place) {
                                place[data.folding] = data.folding
                            }
                            delete data.folding
                        }
                    }
                }, _showFoldedPage: function (c, animate) {
                    var folding = flipMethods._foldingPage.call(this), dd = this.data(), data = dd.f,
                        visible = data.visible;
                    if (folding) {
                        if (!visible || !data.point || data.point.corner != c.corner) {
                            var corner = (data.status == "hover" || data.status == "peel" || data.opts.turn.data().mouseAction) ? c.corner : null;
                            visible = false;
                            if (trigger("start", this, [data.opts, corner]) == "prevented") {
                                return false
                            }
                        }
                        if (animate) {
                            var that = this,
                                point = (data.point && data.point.corner == c.corner) ? data.point : flipMethods._c.call(this, c.corner, 1);
                            this.animatef({
                                from: [point.x, point.y], to: [c.x, c.y], duration: 500, frame: function (v) {
                                    c.x = Math.round(v[0]);
                                    c.y = Math.round(v[1]);
                                    flipMethods._fold.call(that, c)
                                }
                            })
                        } else {
                            flipMethods._fold.call(this, c);
                            if (dd.effect && !dd.effect.turning) {
                                this.animatef(false)
                            }
                        }
                        if (!visible) {
                            switch (data.effect) {
                                case"hard":
                                    data.visible = true;
                                    flipMethods._moveFoldingPage.call(this, true);
                                    data.fpage.show();
                                    if (data.opts.shadows) {
                                        data.bshadow.show()
                                    }
                                    break;
                                case"sheet":
                                    data.visible = true;
                                    data.fparent.show().data().flips++;
                                    flipMethods._moveFoldingPage.call(this, true);
                                    data.fwrapper.show();
                                    if (data.bshadow) {
                                        data.bshadow.show()
                                    }
                                    break
                            }
                        }
                        return true
                    }
                    return false
                }, hide: function () {
                    var data = this.data().f, turnData = data.opts.turn.data(),
                        folding = flipMethods._foldingPage.call(this);
                    switch (data.effect) {
                        case"hard":
                            if (turnData.opts.gradients) {
                                data.bshadowLoc = 0;
                                data.bshadow.remove();
                                data.ashadow.hide()
                            }
                            data.wrapper.transform("");
                            data.fpage.hide();
                            break;
                        case"sheet":
                            if ((--data.fparent.data().flips) === 0) {
                                data.fparent.hide()
                            }
                            this.css({left: 0, top: 0, right: "auto", bottom: "auto"}).transform("");
                            data.wrapper.transform("");
                            data.fwrapper.hide();
                            if (data.bshadow) {
                                data.bshadow.hide()
                            }
                            folding.transform("");
                            break
                    }
                    data.visible = false;
                    return this
                }, hideFoldedPage: function (animate) {
                    var data = this.data().f;
                    if (!data.point) {
                        return
                    }
                    var that = this, p1 = data.point, hide = function () {
                        data.point = null;
                        data.status = "";
                        that.flip("hide");
                        that.trigger("end", [data.opts, false])
                    };
                    if (animate) {
                        var p4 = flipMethods._c.call(this, p1.corner), top = (p1.corner.substr(0, 1) == "t"),
                            delta = (top) ? Math.min(0, p1.y - p4.y) / 2 : Math.max(0, p1.y - p4.y) / 2,
                            p2 = point2D(p1.x, p1.y + delta), p3 = point2D(p4.x, p4.y - delta);
                        this.animatef({
                            from: 0, to: 1, frame: function (v) {
                                var np = bezier(p1, p2, p3, p4, v);
                                p1.x = np.x;
                                p1.y = np.y;
                                flipMethods._fold.call(that, p1)
                            }, complete: hide, duration: 800, hiding: true
                        })
                    } else {
                        this.animatef(false);
                        hide()
                    }
                }, turnPage: function (corner) {
                    var that = this, data = this.data().f, turnData = data.opts.turn.data();
                    corner = {corner: (data.corner) ? data.corner.corner : corner || flipMethods._cAllowed.call(this)[0]};
                    var p1 = data.point || flipMethods._c.call(this, corner.corner, (data.opts.turn) ? turnData.opts.elevation : 0),
                        p4 = flipMethods._c2.call(this, corner.corner);
                    this.trigger("flip").animatef({
                        from: 0, to: 1, frame: function (v) {
                            var np = bezier(p1, p1, p4, p4, v);
                            corner.x = np.x;
                            corner.y = np.y;
                            flipMethods._showFoldedPage.call(that, corner)
                        }, complete: function () {
                            that.trigger("end", [data.opts, true])
                        }, duration: turnData.opts.duration, turning: true
                    });
                    data.corner = null
                }, moving: function () {
                    return "effect" in this.data()
                }, isTurning: function () {
                    return this.flip("moving") && this.data().effect.turning
                }, corner: function () {
                    return this.data().f.corner
                }, _eventStart: function (e) {
                    var data = this.data().f, turn = data.opts.turn;
                    if (!data.corner && !data.disabled && !this.flip("isTurning") && data.opts.page == turn.data().pagePlace[data.opts.page]) {
                        data.corner = flipMethods._isIArea.call(this, e);
                        if (data.corner && flipMethods._foldingPage.call(this)) {
                            this.trigger("pressed", [data.point]);
                            flipMethods._showFoldedPage.call(this, data.corner);
                            return false
                        } else {
                            data.corner = null
                        }
                    }
                }, _eventMove: function (e) {
                    var data = this.data().f;
                    if (!data.disabled) {
                        e = (isTouch) ? e.originalEvent.touches : [e];
                        if (data.corner) {
                            var pos = data.parent.offset();
                            data.corner.x = e[0].pageX - pos.left;
                            data.corner.y = e[0].pageY - pos.top;
                            flipMethods._showFoldedPage.call(this, data.corner)
                        } else {
                            if (data.hover && !this.data().effect && this.is(":visible")) {
                                var point = flipMethods._isIArea.call(this, e[0]);
                                if (point) {
                                    if ((data.effect == "sheet" && point.corner.length == 2) || data.effect == "hard") {
                                        data.status = "hover";
                                        var origin = flipMethods._c.call(this, point.corner, data.opts.cornerSize / 2);
                                        point.x = origin.x;
                                        point.y = origin.y;
                                        flipMethods._showFoldedPage.call(this, point, true)
                                    }
                                } else {
                                    if (data.status == "hover") {
                                        data.status = "";
                                        flipMethods.hideFoldedPage.call(this, true)
                                    }
                                }
                            }
                        }
                    }
                }, _eventEnd: function () {
                    var data = this.data().f, corner = data.corner;
                    if (!data.disabled && corner) {
                        if (trigger("released", this, [data.point || corner]) != "prevented") {
                            flipMethods.hideFoldedPage.call(this, true)
                        }
                    }
                    data.corner = null
                }, disable: function (disable) {
                    flipMethods.setData.call(this, {disabled: disable});
                    return this
                }, hover: function (hover) {
                    flipMethods.setData.call(this, {hover: hover});
                    return this
                }, peel: function (corner, animate) {
                    var data = this.data().f;
                    if (corner) {
                        if ($.inArray(corner, corners.all) == -1) {
                            throw turnError("Corner " + corner + " is not permitted")
                        }
                        if ($.inArray(corner, flipMethods._cAllowed.call(this)) != -1) {
                            var point = flipMethods._c.call(this, corner, data.opts.cornerSize / 2);
                            data.status = "peel";
                            flipMethods._showFoldedPage.call(this, {corner: corner, x: point.x, y: point.y}, animate)
                        }
                    } else {
                        data.status = "";
                        flipMethods.hideFoldedPage.call(this, animate)
                    }
                    return this
                }
            };

        function dec(that, methods, args) {
            if (!args[0] || typeof(args[0]) == "object") {
                return methods.init.apply(that, args)
            } else {
                if (methods[args[0]]) {
                    return methods[args[0]].apply(that, Array.prototype.slice.call(args, 1))
                } else {
                    throw turnError(args[0] + " is not a method or property")
                }
            }
        }

        function divAtt(top, left, zIndex, overf) {
            return {
                css: {
                    position: "absolute",
                    top: top,
                    left: left,
                    overflow: overf || "hidden",
                    zIndex: zIndex || "auto"
                }
            }
        }

        function bezier(p1, p2, p3, p4, t) {
            var a = 1 - t, b = a * a * a, c = t * t * t;
            return point2D(Math.round(b * p1.x + 3 * t * a * a * p2.x + 3 * t * t * a * p3.x + c * p4.x), Math.round(b * p1.y + 3 * t * a * a * p2.y + 3 * t * t * a * p3.y + c * p4.y))
        }

        function rad(degrees) {
            return degrees / 180 * PI
        }

        function deg(radians) {
            return radians / PI * 180
        }

        function point2D(x, y) {
            return {x: x, y: y}
        }

        function rotationAvailable() {
            var parts;
            if ((parts = /AppleWebkit\/([0-9\.]+)/i.exec(navigator.userAgent))) {
                var webkitVersion = parseFloat(parts[1]);
                return (webkitVersion > 534.3)
            } else {
                return true
            }
        }

        function translate(x, y, use3d) {
            return (has3d && use3d) ? " translate3d(" + x + "px," + y + "px, 0px) " : " translate(" + x + "px, " + y + "px) "
        }

        function rotate(degrees) {
            return " rotate(" + degrees + "deg) "
        }

        function has(property, object) {
            return Object.prototype.hasOwnProperty.call(object, property)
        }

        function getPrefix() {
            var vendorPrefixes = ["Moz", "Webkit", "Khtml", "O", "ms"], len = vendorPrefixes.length, vendor = "";
            while (len--) {
                if ((vendorPrefixes[len] + "Transform") in document.body.style) {
                    vendor = "-" + vendorPrefixes[len].toLowerCase() + "-"
                }
            }
            return vendor
        }

        function getTransitionEnd() {
            var t, el = document.createElement("fakeelement"), transitions = {
                transition: "transitionend",
                OTransition: "oTransitionEnd",
                MSTransition: "transitionend",
                MozTransition: "transitionend",
                WebkitTransition: "webkitTransitionEnd"
            };
            for (t in transitions) {
                if (el.style[t] !== undefined) {
                    return transitions[t]
                }
            }
        }

        function gradient(obj, p0, p1, colors, numColors) {
            var j, cols = [];
            if (vendor == "-webkit-") {
                for (j = 0; j < numColors; j++) {
                    cols.push("color-stop(" + colors[j][0] + ", " + colors[j][1] + ")")
                }
                obj.css({"background-image": "-webkit-gradient(linear, " + p0.x + "% " + p0.y + "%," + p1.x + "% " + p1.y + "%, " + cols.join(",") + " )"})
            } else {
                p0 = {x: p0.x / 100 * obj.width(), y: p0.y / 100 * obj.height()};
                p1 = {x: p1.x / 100 * obj.width(), y: p1.y / 100 * obj.height()};
                var dx = p1.x - p0.x, dy = p1.y - p0.y, angle = Math.atan2(dy, dx), angle2 = angle - Math.PI / 2,
                    diagonal = Math.abs(obj.width() * Math.sin(angle2)) + Math.abs(obj.height() * Math.cos(angle2)),
                    gradientDiagonal = Math.sqrt(dy * dy + dx * dx),
                    corner = point2D((p1.x < p0.x) ? obj.width() : 0, (p1.y < p0.y) ? obj.height() : 0),
                    slope = Math.tan(angle), inverse = -1 / slope,
                    x = (inverse * corner.x - corner.y - slope * p0.x + p0.y) / (inverse - slope),
                    c = {x: x, y: inverse * x - inverse * corner.x + corner.y},
                    segA = (Math.sqrt(Math.pow(c.x - p0.x, 2) + Math.pow(c.y - p0.y, 2)));
                for (j = 0; j < numColors; j++) {
                    cols.push(" " + colors[j][1] + " " + ((segA + gradientDiagonal * colors[j][0]) * 100 / diagonal) + "%")
                }
                obj.css({"background-image": "white"})
            }
        }

        function trigger(eventName, context, args) {
            var event = $.Event(eventName);
            context.trigger(event, args);
            if (event.isDefaultPrevented()) {
                return "prevented"
            } else {
                if (event.isPropagationStopped()) {
                    return "stopped"
                } else {
                    return ""
                }
            }
        }

        function turnError(message) {
            function TurnJsError(message) {
                this.name = "TurnJsError";
                this.message = message
            }

            TurnJsError.prototype = new Error();
            TurnJsError.prototype.constructor = TurnJsError;
            return new TurnJsError(message)
        }

        function findPos(obj) {
            var offset = {top: 0, left: 0};
            do {
                offset.left += obj.offsetLeft;
                offset.top += obj.offsetTop
            } while ((obj = obj.offsetParent));
            return offset
        }

        function hasHardPage() {
            return (navigator.userAgent.indexOf("MSIE 9.0") == -1)
        }

        window.requestAnim = (function () {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
                    window.setTimeout(callback, 1000 / 60)
                }
        })();
        $.extend($.fn, {
            flip: function () {
                return dec($(this[0]), flipMethods, arguments)
            }, turn: function () {
                return dec($(this[0]), turnMethods, arguments)
            }, transform: function (transform, origin) {
                var properties = {};
                if (origin) {
                    properties[vendor + "transform-origin"] = origin
                }
                properties[vendor + "transform"] = transform;
                return this.css(properties)
            }, animatef: function (point) {
                var data = this.data();
                if (data.effect) {
                    data.effect.stop()
                }
                if (point) {
                    if (!point.to.length) {
                        point.to = [point.to]
                    }
                    if (!point.from.length) {
                        point.from = [point.from]
                    }
                    var diff = [], len = point.to.length, animating = true, that = this, time = (new Date()).getTime(),
                        frame = function () {
                            if (!data.effect || !animating) {
                                return
                            }
                            var v = [], timeDiff = Math.min(point.duration, (new Date()).getTime() - time);
                            for (var i = 0; i < len; i++) {
                                v.push(data.effect.easing(1, timeDiff, point.from[i], diff[i], point.duration))
                            }
                            point.frame((len == 1) ? v[0] : v);
                            if (timeDiff == point.duration) {
                                delete data.effect;
                                that.data(data);
                                if (point.complete) {
                                    point.complete()
                                }
                            } else {
                                window.requestAnim(frame)
                            }
                        };
                    for (var i = 0; i < len; i++) {
                        diff.push(point.to[i] - point.from[i])
                    }
                    data.effect = $.extend({
                        stop: function () {
                            animating = false
                        }, easing: function (x, t, b, c, data) {
                            return c * Math.sqrt(1 - (t = t / data - 1) * t) + b
                        }
                    }, point);
                    this.data(data);
                    frame()
                } else {
                    delete data.effect
                }
            }
        });
        $.isTouch = isTouch;
        $.mouseEvents = mouseEvents;
        $.cssPrefix = getPrefix;
        $.cssTransitionEnd = getTransitionEnd;
        $.findPos = findPos
    })(jQuery);
    (function (mod) {
        if (typeof module === "object" && typeof exports !== undefined) {
            module.exports = mod($, _)
        } else {
            if (typeof define === "function" && define.amd) {
                return define(["jquery", "underscore"], mod($, _))
            } else {
                (this || window).TTReader = mod($, _)
            }
        }
    }(function ($, _) {
        var trial = $("#trial").val() ? true : false;
        var reader_able = true;

        function TTReader(place, options) {
            if (!(this instanceof TTReader)) {
                return new TTReader(place, options)
            }
            var me = this;
            var defaults = {
                headerCreator: defaultHeaderCreator,
                footerCreator: defaultFooterCreator,
                ashadow: "white",
                maxFlipPages: 10,
                gradients: true,
                duration: 500,
                elevation: 100
            };
            var measure = me.measure = createElement("div", null, "TTReader-measure");
            var pages = me.pages = createElement("div", null, "TTReader-pages");
            var mask = createElement("div", null, "TTReader-content-mask");
            var content = me.content = createElement("div", [measure, pages, mask], "TTReader-content", "height:100%");
            var reader = me.reader = createElement("div", [content], "TTReader");
            place.appendChild(reader);
            me.options = options = options ? copyObj(options, defaults) : defaults;
            if (typeof options.minMouseMoveDistance !== "number") {
                if (/^\d+%$/.test(options.minMouseMoveDistance)) {
                    options.minMouseMoveDistance = parseFloat(options.minMouseMoveDistance) * content.clientWidth
                }
                options.minMouseMoveDistance = parseInt(options.minMouseMoveDistance)
            }
            options.minMouseMoveDistance = Math.abs(options.minMouseMoveDistance);
            var doc = me.doc = new Doc(options.data || {}, me);
            $(window).resize(_.debounce(function () {
                me.updatePages()
            }, 500));
            if ($.isTouch) {
                var touchX = 0;
                var touchX1 = 0;
                $(".TTReader-content").bind("touchstart", function (e) {
                    if (me.fireEvent(ttreaderEvents.BeforeTurning)) {
                        return
                    }
                    if (null == me.flipbook) {
                        return
                    }
                    var _touch = e.originalEvent.targetTouches[0];
                    touchX = _touch.pageX
                });
                $(".TTReader-content").bind("touchmove", function (e) {
                    var _touch = e.originalEvent.targetTouches[0];
                    touchX1 = _touch.pageX
                });
                $(".TTReader-content").bind("touchend", function (e) {
                    var readerWidth = $("#content").outerWidth();
                    if ($(".TTReader-content").outerWidth() == $("#content").outerWidth() && !$("#bookMenu").is(":hidden")) {
                        bookMenuClose(200);
                        return
                    }
                    if (touchX1 !== 0) {
                        if ((touchX1 - touchX) > 30) {
                            var pastPage = me.flipbook.turn("page");
                            me.flipbook.turn("previous");
                            var nowPage = me.flipbook.turn("page");
                            touchX1 = touchX = 0;
                            if (is_listening && (pastPage != nowPage)) {
                                throttLelistenReady();
                                return false
                            }
                        } else {
                            if ((touchX - touchX1) > 30) {
                                var pastPage = me.flipbook.turn("page");
                                if (reader_able) {
                                    me.flipbook.turn("next")
                                }
                                var nowPage = me.flipbook.turn("page");
                                touchX1 = touchX = 0;
                                if (is_listening && (pastPage != nowPage)) {
                                    throttLelistenReady();
                                    return false
                                }
                            }
                        }
                    } else {
                        if (touchX > readerWidth / 3 && touchX < readerWidth * 2 / 3) {
                            if (is_listening) {
                                appUtils.listeningStop("0");
                                return false
                            }
                            if (!$(".progressForm").is(":hidden")) {
                                $(".progressForm").addClass("none");
                                $("#progress-btn").parent().removeClass("selected")
                            } else {
                                bookMenuOpen(200)
                            }
                        } else {
                            if (touchX < readerWidth / 3) {
                                var pastPage = me.flipbook.turn("page");
                                me.flipbook.turn("previous");
                                var nowPage = me.flipbook.turn("page");
                                if (is_listening && (pastPage != nowPage)) {
                                    throttLelistenReady();
                                    return false
                                }
                            } else {
                                if (touchX > readerWidth * 2 / 3) {
                                    var pastPage = me.flipbook.turn("page");
                                    if (reader_able) {
                                        me.flipbook.turn("next")
                                    }
                                    var nowPage = me.flipbook.turn("page");
                                    if (is_listening && (pastPage != nowPage)) {
                                        throttLelistenReady();
                                        return false
                                    }
                                }
                            }
                        }
                    }
                })
            } else {
                $(".TTReader-content").on("mousedown", function (e) {
                    if (me.fireEvent(ttreaderEvents.BeforeTurning)) {
                        return
                    }
                    if (null == me.flipbook) {
                        return
                    }
                    if (e.offsetX <= content.clientWidth * 1 / 3) {
                        me.flipbook.turn("previous")
                    } else {
                        if (e.offsetX >= content.clientWidth * 2 / 3) {
                            if (reader_able) {
                                me.flipbook.turn("next")
                            }
                        }
                    }
                })
            }
            me.updatePages()
        }

        TTReader.prototype = {
            getChapterPages: function (chapter) {
                var chapters = this.doc.chapters;
                if (_.isNumber(chapter)) {
                    if (chapter < 0 || chapter >= chapters.length) {
                        return null
                    }
                    chapter = chapters[chapter]
                }
                if (null == chapter) {
                    return null
                }
                return chapter.getPages(this)
            }, prevPage: function (page) {
                var flippages = this.flippages;
                var curPage = page || (flippages != null ? flippages[0] : null);
                if (null == curPage) {
                    return null
                }
                if (curPage.index > 0) {
                    var pages = this.getChapterPages(curPage.chapter);
                    var p = pages[curPage.index - 1];
                    return pages[curPage.index - 1]
                }
                if (curPage.chapter.data.i > 0) {
                    pages = this.getChapterPages(curPage.chapter.data.i - 1);
                    return pages[pages.length - 1]
                }
                return null
            }, nextPage: function (page) {
                var flippages = this.flippages;
                var curPage = page || (flippages != null ? flippages[flippages.length - 1] : null);
                if (null == curPage) {
                    return null
                }
                var pages = this.getChapterPages(curPage.chapter);
                if (curPage.index + 1 < pages.length) {
                    return pages[curPage.index + 1]
                }
                pages = this.getChapterPages(curPage.chapter.data.i + 1);
                return pages != null ? pages[0] : null
            }, highlightKeyword: function (keyword) {
                var me = this;
                if (null == me.flipbook) {
                    return
                }
                var pagenum = me.flipbook.turn("page");
                var pageBody = $("div.TTReader-page.p" + pagenum).find("div.TTReader-page-body");
                var orgHtml = pageBody.html().replace(/\r?\n/g, "");
                var hglHtml = addHighlightClass(orgHtml, keyword, pageBody.text());
                this.highlightedPage = {elt: pageBody, html: orgHtml};
                pageBody.html(hglHtml)
            }, updatePages: function (offset, keyword) {
                var progress = Math.min(offset != null ? offset : this.doc.progress, this.doc.text.length);
                var chapters = this.doc.chapters;
                var chapter = binarySearchRange(chapters, progress);
                this.contentWidth = this.content.clientWidth;
                this.contentHeight = this.content.clientHeight;
                if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                    $("#bookMenu").addClass("mobile-landscape-bookMenu");
                    $("#MenuPanel").addClass("mobile-landscape-MenuPanel");
                    if (!$("#bookMenu").is(":hidden")) {
                        $("#bookMenu").css({bottom: "0"})
                    } else {
                        var offset = $("#bookMenu").outerHeight();
                        $("#bookMenu").css({bottom: -offset})
                    }
                } else {
                    $("#bookMenu").removeClass("mobile-landscape-bookMenu");
                    $("#bookMenu").css({bottom: "initial"});
                    $("#MenuPanel").removeClass("mobile-landscape-MenuPanel");
                    if (!$("#bookMenu").is(":hidden")) {
                        $("#bookMenu").css({left: "0"})
                    }
                }
                var pages = this.getChapterPages(chapter);
                if (null == pages) {
                    return
                }
                if (pages.length <= 1) {
                    var morePages = this.getChapterPages(chapter.data.i + 1);
                    if ((null != morePages) && (morePages.length > 0)) {
                        pages = pages.concat(morePages)
                    }
                }
                var i = pages.length, flippages = Array(i);
                while (i--) {
                    flippages[i] = pages[i]
                }
                var page = binarySearchRange(flippages, progress), pagenum = page.index + 1;
                if (1 == pagenum) {
                    var prevPage = this.prevPage(page);
                    if (null != prevPage) {
                        flippages.unshift(prevPage);
                        pagenum += 1
                    }
                }
                if (flippages.length == pagenum) {
                    var morePages = this.getChapterPages(chapter.data.i + 1);
                    if ((null != morePages) && (morePages.length > 0)) {
                        flippages = flippages.concat(morePages)
                    }
                }
                this.setFlipbookPages(flippages, pagenum);
                signal(this, ttreaderEvents.PageIsReady, page);
                if (keyword) {
                    this.highlightKeyword(keyword)
                } else {
                    this.highlightedPage = null
                }
                if (trial) {
                    reader_able = flippages[pagenum].start > this.doc.trial ? false : true;
                    signal(this, ttreaderEvents.ReachingTrialEnd, reader_able)
                }
            }, setFlipbookPages: function (flippages, pagenum) {
                var me = this;
                if (!flippages || 0 == flippages.length) {
                    return
                }
                pagenum = Math.min(pagenum, flippages.length);
                if (me.flipbook) {
                    me.flipbook.turn("destroy")
                }
                var newPages = createElement("div", null, "TTReader-pages");
                for (var i = 0; i < flippages.length; ++i) {
                    newPages.appendChild(flippages[i].elt.cloneNode(true))
                }
                $(me.content).find(me.pages).replaceWith(newPages);
                me.pages = newPages;
                me.flipbook = $(me.pages);
                me.flippages = flippages;
                me.flipbook.turn({
                    page: pagenum,
                    display: "single",
                    gradients: me.options.gradients,
                    ashadow: me.options.ashadow,
                    duration: me.options.duration,
                    elevation: me.options.elevation,
                    when: {
                        last: function (event) {
                            me.onReachingLastPage()
                        }, start: function (event, pageObj, corner) {
                            var flag = me.fireEvent(ttreaderEvents.BeforeTurning);
                            if (ie && ie_version < 9) {
                                event.preventDefault()
                            }
                            if (trial) {
                                var next = pageObj.next > pageObj.page ? true : false;
                                var page = next ? pageObj.page + 1 : pageObj.page - 1;
                                var start = flippages[Math.max(0, Math.min(page, flippages.length - 1))].start;
                                reader_able = start > me.doc.trial ? false : true;
                                signal(me, ttreaderEvents.ReachingTrialEnd, reader_able)
                            }
                        }, turned: function (event, page, view) {
                            var flippages = me.flippages;
                            if (null != me.highlightedPage) {
                                var pageBody = me.highlightedPage.elt;
                                pageBody.html(me.highlightedPage.html);
                                me.highlightedPage = null
                            }
                            if ((page <= 1) && ((flippages[0].index > 0) || (flippages[0].chapter.data.i > 0))) {
                                me.onReachingFirstPage()
                            }
                            var old_progress = me.doc.progress;
                            me.doc.progress = flippages[Math.max(0, Math.min(page, flippages.length) - 1)].start;
                            signal(me, ttreaderEvents.ProgressChanged, old_progress, me.doc.progress)
                        }
                    }
                });
                $(document).unbind($.mouseEvents.up);
                var old_progress = me.doc.progress;
                me.doc.progress = flippages[pagenum - 1].start;
                if (me.doc.progress != old_progress) {
                    signal(me, ttreaderEvents.ProgressChanged, old_progress, me.doc.progress)
                }
            }, onReachingLastPage: function () {
                var me = this, flipbook = me.flipbook, flippages = me.flippages;
                if (null == flipbook || null == flippages) {
                    return
                }
                var nextPage = me.nextPage();
                if (null == nextPage) {
                    return
                }
                flippages.push(nextPage);
                flipbook.turn("addPage", nextPage.elt.cloneNode(true))
            }, onReachingFirstPage: function () {
                var me = this, flipbook = me.flipbook, flippages = me.flippages;
                if (null == flipbook || null == flippages) {
                    return
                }
                var prevPage = me.prevPage();
                if (null == prevPage) {
                    return
                }
                var pagenum = 1, curChapter = prevPage.chapter;
                while (prevPage) {
                    flippages.unshift(prevPage);
                    ++pagenum;
                    if (prevPage.chapter != curChapter) {
                        break
                    }
                    prevPage = me.prevPage(prevPage)
                }
                _.defer(function () {
                    me.setFlipbookPages(flippages, pagenum)
                })
            }, fireEvent: function (type, args) {
                if (ttreaderEvents.BeforeTurning == type) {
                    var args = args || {preventDefault: false};
                    signal(this, ttreaderEvents.BeforeTurning, args);
                    return args.preventDefault
                }
            }
        };
        var Chapter = function (doc, data) {
            this.doc = doc;
            this.data = data || {i: 0, b: 0, e: doc.text.length};
            this.cache = {pages: null}
        };
        Chapter.prototype.getPages = function (reader, width, height, headerCreator, footerCreator) {
            reader = reader || this.doc.reader;
            if (null == reader) {
                return this.cache.pages
            }
            width = width || reader.contentWidth, height = height || reader.contentHeight;
            if (null == width || null == height) {
                return this.cache.pages
            }
            if (null == headerCreator) {
                headerCreator = reader.options.headerCreator
            }
            if (null == footerCreator) {
                footerCreator = reader.options.footerCreator
            }
            if (this.cache.pages && this.cache.width == width && this.cache.height == height && this.cache.headerCreator == headerCreator && this.cache.footerCreator == footerCreator) {
                return this.cache.pages
            }
            this.cache.width = width, this.cache.height = height;
            this.cache.headerCreator = headerCreator, this.cache.footerCreator = footerCreator;
            var offset = 0;
            var pages = this.cache.pages = new Array(createPage(this, 0));
            var paragraphs = this.data.p, nParagraph = paragraphs.length;
            for (var i = 0; i < nParagraph; ++i) {
                if (paragraphs[i].b == paragraphs[i].e) {
                    continue
                }
                appendParagraph(this, paragraphs[i], pages, height, width)
            }
            if (this.data.l || (this.data.e >= this.doc.text.length)) {
                pages.push(createLastPage(this))
            }
            return this.cache.pages
        };
        function defaultHeaderCreator(chapter, index, pageStart, pageEnd) {
            var bookTitle = createElement("span", chapter.doc.title, "TTReader-book-title");
            var chapterTitle = createElement("span", chapter.data.title, "TTReader-chapter-title");
            return createElement("div", [bookTitle], "TTReader-page-header")
        }

        function defaultFooterCreator(chapter, index, pageStart, pageEnd) {
            return createElement("div", [document.createTextNode((pageEnd * 100 / chapter.doc.text.length).toFixed(1) + " %")], "TTReader-page-footer")
        }

        function createPage(chapter, index, start, end, headerCreator, footerCreator, element) {
            if (null == index) {
                index = chapter.cache.pages != null ? chapter.cache.pages.length : 0
            }
            if (null == start) {
                start = chapter.data.b
            }
            if (null == end) {
                end = start
            }
            if (null == headerCreator) {
                headerCreator = chapter.cache.headerCreator || chapter.doc.reader.options.headerCreator
            }
            if (null == footerCreator) {
                footerCreator = chapter.cache.footerCreator || chapter.doc.reader.options.footerCreator
            }
            var header = headerCreator(chapter, index, start, end);
            var footer = footerCreator(chapter, index, start, end);
            var pageBody = createElement("div", null, "TTReader-page-body");
            var elt = element || createElement("div", [header, pageBody, footer], "TTReader-page");
            var page = {
                chapter: chapter,
                index: index,
                start: start,
                end: end,
                elt: elt,
                header: header,
                footer: footer,
                body: pageBody
            };
            return page
        }

        function createLastPage(chapter, index, headerCreator, footerCreator, element) {
            if (null == index) {
                index = chapter.cache.pages != null ? chapter.cache.pages.length : 0
            }
            if (null == headerCreator) {
                headerCreator = chapter.cache.headerCreator || chapter.doc.reader.options.headerCreator
            }
            if (null == footerCreator) {
                footerCreator = chapter.cache.footerCreator || chapter.doc.reader.options.footerCreator
            }
            var textLen = chapter.doc.text.length;
            var header = headerCreator(chapter, index, textLen, textLen);
            var footer = footerCreator(chapter, index, textLen, textLen);
            var pageBody = createElement("div", "全文完", "TTReader-lastpage-body");
            var elt = element || createElement("div", [header, pageBody, footer], "TTReader-lastpage");
            var page = {
                chapter: chapter,
                index: index,
                start: textLen,
                end: textLen,
                elt: elt,
                header: header,
                footer: footer,
                body: pageBody
            };
            return page
        }

        function getPageBodyHeight(page, height) {
            return height - page.header.clientHeight - page.footer.clientHeight
        }

        function appendParagraph(chapter, paragraph, pages, height, width) {
            var doc = chapter.doc, textLen = doc.text.length;
            var org_page_count = pages.length || pages.push(createPage(chapter, pages.length, paragraph.b));
            var page = pages[pages.length - 1], pageEl = page.body;
            removeChildrenAndAdd(doc.reader.measure, page.elt);
            var pageBodyHeight = getPageBodyHeight(page, height);
            var element = buildParagraphElement(doc, paragraph);
            pageEl.appendChild(element);
            var rect = pageEl.getBoundingClientRect();
            var rectHeight = (null == rect.height) ? (rect.bottom - rect.top) : rect.height;
            if (rectHeight <= pageBodyHeight) {
                page.end = paragraph.e;
                return 0
            }
            if ("image" == paragraph.u) {
                pageEl.removeChild(element);
                page = createPage(chapter, pages.length, page.end), pages.push(page);
                pageEl = page.body, pageEl.appendChild(element);
                removeChildrenAndAdd(doc.reader.measure, page.elt);
                pageBodyHeight = getPageBodyHeight(page, height);
                var rect = pageEl.getBoundingClientRect();
                var rectHeight = (null == rect.height) ? (rect.bottom - rect.top) : rect.height;
                if (rectHeight > pageBodyHeight) {
                    page = createPage(chapter, pages.length, page.end);
                    pages.push(page)
                }
                return pages.length - org_page_count
            }
            var spans = paragraph.s.slice(0);
            var paddingSpans = new Array();
            while (spans.length > 0) {
                var span = spans.shift();
                var rect = span.elt.getBoundingClientRect();
                if (rect.bottom <= pageBodyHeight) {
                    paddingSpans.push(span);
                    continue
                }
                var textNode = span.elt.firstChild;
                var contentLen = (textNode.textContent || textNode.nodeValue).length;
                if (rect.top < pageBodyHeight) {
                    var lastRect = getBoundingClientRect(textNode, contentLen - 1, contentLen);
                    if (lastRect.bottom <= pageBodyHeight) {
                        paddingSpans.push(span);
                        continue
                    }
                    var firstRect = getBoundingClientRect(textNode, 0, 1);
                    if (firstRect.bottom <= pageBodyHeight) {
                        var pageEnd = findLastAbove({x: width + 1, y: pageBodyHeight}, span, firstRect, lastRect);
                        pageEnd.offset += 1;
                        var headSpan = {
                            b: span.b,
                            e: pageEnd.offset,
                            elt: createElement("span", [document.createTextNode(doc.text.substring(span.b, pageEnd.offset))], span.c, span.y)
                        };
                        var tailSpan = {
                            b: pageEnd.offset,
                            e: span.e,
                            elt: createElement("span", [document.createTextNode(doc.text.substring(pageEnd.offset, span.e))], span.c, span.y)
                        };
                        paddingSpans.push(headSpan);
                        spans.unshift(tailSpan), element.replaceChild(tailSpan.elt, span.elt)
                    } else {
                        spans.unshift(span)
                    }
                } else {
                    spans.unshift(span)
                }
                if (paddingSpans.length > 0) {
                    var paddingElement = buildEmptyTextParagraphElement(paragraph.c, paragraph.y);
                    for (var i = 0; i < paddingSpans.length; ++i) {
                        paddingElement.appendChild(paddingSpans[i].elt)
                    }
                    paddingSpans = new Array();
                    pageEl.replaceChild(paddingElement, element)
                }
                if (spans.length > 0) {
                    if (element.parentNode == pageEl) {
                        pageEl.removeChild(element)
                    }
                    page.end = spans[0].b;
                    page = createPage(chapter, pages.length, spans[0].b), pages.push(page);
                    pageEl = page.body, pageEl.appendChild(element);
                    removeChildrenAndAdd(doc.reader.measure, page.elt);
                    pageBodyHeight = getPageBodyHeight(page, height)
                }
            }
            pages[pages.length - 1].end = paragraph.e;
            return pages.length - org_page_count
        }

        function findLastAbove(coord, span, firstRect, lastRect) {
            var mid, midRect, textNode = span.elt.firstChild;
            var contentLen = textNode.length;
            if (null != textNode.textContent) {
                contentLen = textNode.textContent.length
            }
            var lft = span.b, lftRect = firstRect || getBoundingClientRect(textNode, 0, 1);
            var rgt = span.e, rgtRect = lastRect || getBoundingClientRect(textNode, contentLen - 1, contentLen);
            while (rgt - lft > 1) {
                mid = parseInt((lft + rgt) / 2);
                midRect = getBoundingClientRect(textNode, mid - span.b, mid - span.b + 1);
                if (midRect.bottom >= coord.y) {
                    rgt = mid, rgtRect = midRect
                } else {
                    if (rgtRect.bottom > coord.y || midRect.right <= coord.x) {
                        lft = mid, lftRect = midRect
                    } else {
                        rgt = mid, rgtRect = midRect
                    }
                }
            }
            return {offset: lft, rect: lftRect}
        }

        function buildEmptyTextParagraphElement(cls, style) {
            return createElement("p", null, cls, style)
        }

        function buildParagraphElement(doc, paragraph) {
            var element, type = paragraph.u || "text";
            if ("image" == type) {
                element = paragraph.elt = createElement("img", null, paragraph.c, paragraph.y);
                element.src = paragraph.src
            } else {
                element = paragraph.elt = buildEmptyTextParagraphElement(paragraph.c, paragraph.y);
                var spans = paragraph.s = paragraph.s || new Array({b: paragraph.b, e: paragraph.e});
                var nSpan = spans.length;
                for (var i = 0; i < nSpan; ++i) {
                    var span = spans[i];
                    span.elt = span.elt || createElement("span", [document.createTextNode(doc.text.substring(span.b, span.e))], span.c, span.y);
                    element.appendChild(span.elt)
                }
            }
            return element
        }

        var Doc = TTReader.Doc = function (data, reader) {
            if (!(this instanceof Doc)) {
                return new Doc(data, reader)
            }
            this.reader = reader;
            this.title = data.title || "";
            this.text = data.text || "";
            this.catalog = data.catalog || "";
            this.progress = data.progress || 0;
            this.trial = data.trial;
            var chapters = this.chapters = new Array(), nChapters = data.chapters.length;
            for (var i = 0; i < nChapters; ++i) {
                chapters.push(new Chapter(this, data.chapters[i]))
            }
        };

        function binarySearchRange(vec, pos) {
            if (!vec) {
                return null
            }
            var start, end, mid, lft = 0, rgt = vec.length - 1;
            while (rgt - lft > 1) {
                mid = parseInt((lft + rgt) / 2);
                start = vec[mid].start != null ? vec[mid].start : vec[mid].data.b;
                end = vec[mid].end != null ? vec[mid].end : vec[mid].data.e;
                if (end <= pos) {
                    lft = mid
                } else {
                    if (start <= pos) {
                        return vec[mid]
                    } else {
                        rgt = mid
                    }
                }
            }
            start = vec[lft].start != null ? vec[lft].start : vec[lft].data.b;
            end = vec[lft].end != null ? vec[lft].end : vec[lft].data.e;
            if (start <= pos && end > pos) {
                return vec[lft]
            }
            return vec[rgt]
        }

        function addHighlightClass(html, keyword, text) {
            if (null == text) {
                text = $(html).text()
            }
            var repat = parseKeywordStr(keyword);
            if (null == repat) {
                return text
            }
            var segs = new Array();
            var char2seg = new Array();
            var gt = 0, lt = html.indexOf("<"), charIndex = 0, htmlLen = html.length;
            while (lt >= 0) {
                for (var i = gt; i < lt; ++i) {
                    char2seg.push(segs.length);
                    segs.push(html.charAt(i))
                }
                gt = html.indexOf(">", lt);
                if (gt < 0) {
                    gt = htmlLen - 1
                }
                segs.push(html.substring(lt, ++gt));
                lt = html.indexOf("<", gt)
            }
            if (gt < htmlLen) {
                for (var i = gt; i < htmlLen; ++i) {
                    char2seg.push(segs.length);
                    segs.push(html.charAt(i))
                }
            }
            var match;
            while (match = repat.exec(text)) {
                for (var i = 0; i < match[0].length; ++i) {
                    var segIndex = char2seg[match.index + i];
                    segs[segIndex] = '<span class="highlight">'.concat(segs[segIndex], "</span>")
                }
            }
            var result = "", segsLen = segs.length;
            for (var i = 0; i < segsLen; ++i) {
                result += segs[i]
            }
            return result
        }

        function bookMenuOpen(speed) {
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                $("#bookMenu").addClass("mobile-landscape-bookMenu");
                $("#bookMenu").css({display: "block"}).animate({bottom: "0"}, speed)
            } else {
                $("#bookMenu").css({display: "block"}).animate({left: "0"}, speed)
            }
        }

        function bookMenuClose(speed) {
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                var offset = $("#bookMenu").outerHeight();
                $("#bookMenu").animate({bottom: -offset}, speed, function () {
                    $("#bookMenu").css({display: "none"}).removeClass("mobile-landscape-bookMenu")
                })
            } else {
                var offset = $("#bookMenu").outerWidth();
                $("#bookMenu").animate({left: -offset}, speed, function () {
                    $("#bookMenu").css({display: "none"})
                })
            }
        }

        function isFunction(obj) {
            if (typeof(object) === "function") {
                return true
            }
            return !!(obj && obj.constructor && obj.call && obj.apply)
        }

        var gecko = /gecko\/\d/i.test(navigator.userAgent);
        var ie_upto10 = /MSIE \d/.test(navigator.userAgent);
        var ie_11up = /Trident\/(?:[7-9]|\d{2,})\..*rv:(\d+)/.exec(navigator.userAgent);
        var ie = ie_upto10 || ie_11up;
        var ie_version = ie && (ie_upto10 ? document.documentMode || 6 : ie_11up[1]);
        var webkit = /WebKit\//.test(navigator.userAgent);
        var qtwebkit = webkit && /Qt\/\d+\.\d+/.test(navigator.userAgent);
        var chrome = /Chrome\//.test(navigator.userAgent);
        var presto = /Opera\//.test(navigator.userAgent);
        var safari = /Apple Computer/.test(navigator.vendor);
        var mac_geMountainLion = /Mac OS X 1\d\D([8-9]|\d\d)\D/.test(navigator.userAgent);
        var phantom = /PhantomJS/.test(navigator.userAgent);
        var ios = /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent);
        var mobile = ios || /Android|webOS|BlackBerry|Opera Mini|Opera Mobi|IEMobile/i.test(navigator.userAgent);
        var mac = ios || /Mac/.test(navigator.platform);
        var windows = /win/i.test(navigator.platform);
        var presto_version = presto && navigator.userAgent.match(/Version\/(\d*\.\d*)/);
        if (presto_version) {
            presto_version = Number(presto_version[1])
        }
        if (presto_version && presto_version >= 15) {
            presto = false;
            webkit = true
        }
        var flipCtrlCmd = mac && (qtwebkit || presto && (presto_version == null || presto_version < 12.11));
        var captureRightClick = gecko || (ie && ie_version >= 9);

        function createElement(tag, content, className, style) {
            var e = document.createElement(tag);
            if (className) {
                e.className = className
            }
            if (style) {
                e.style.cssText = style
            }
            if (typeof content == "string") {
                e.appendChild(document.createTextNode(content))
            } else {
                if (content) {
                    for (var i = 0; i < content.length; ++i) {
                        e.appendChild(content[i])
                    }
                }
            }
            return e
        }

        function copyObj(obj, target, overwrite) {
            if (!target) {
                target = {}
            }
            for (var prop in obj) {
                if (obj.hasOwnProperty(prop) && (overwrite !== false || !target.hasOwnProperty(prop))) {
                    target[prop] = obj[prop]
                }
            }
            return target
        }

        function removeChildren(e) {
            var count = e.childNodes.length;
            while (count--) {
                e.removeChild(e.firstChild)
            }
            return e
        }

        function removeChildrenAndAdd(parent, e) {
            if (parent.firstChild == e && parent.lastChild == e) {
                return e
            }
            return removeChildren(parent).appendChild(e)
        }

        var nullRect = {left: 0, right: 0, top: 0, bottom: 0};

        function createRange(domNode, start, end, endNode) {
            if (document.createRange) {
                var r = document.createRange();
                r.setEnd(endNode || domNode, end);
                r.setStart(domNode, start);
                return r
            }
            var r = document.body.createTextRange();
            try {
                r.moveToElementText(domNode.parentNode)
            } catch (e) {
                return r
            }
            r.collapse(true);
            r.moveEnd("character", end);
            r.moveStart("character", start);
            return r
        }

        function getBoundingClientRect(domNode, start, end) {
            var rect = nullRect;
            if (ie) {
                var rects = createRange(domNode, start, end).getClientRects();
                rect = rects.length ? rects[0] : nullRect
            } else {
                rect = createRange(domNode, start, end).getBoundingClientRect() || nullRect
            }
            return rect
        }

        var parseKeywordStr = TTReader.parseKeywordStr = function (keywordStr) {
            if (keywordStr == null || keywordStr == '""') {
                return null
            }
            keywordStr = $.trim(keywordStr);
            if ((keywordStr.length > 2) && (keywordStr.charAt(0) == '"') && (keywordStr.charAt(keywordStr.length - 1) == '"')) {
                return new RegExp("(" + keywordStr + ")", "g")
            }
            return new RegExp("(" + _.sortBy(_.uniq(keywordStr.split(/\s+/)), function (s) {
                    return -s.length
                }).join("|") + ")", "g")
        };
        var on = TTReader.on = function (emitter, type, f) {
            if (emitter.addEventListener) {
                emitter.addEventListener(type, f, false)
            } else {
                if (emitter.attachEvent) {
                    emitter.attachEvent("on" + type, f)
                } else {
                    var map = emitter._handlers || (emitter._handlers = {});
                    var arr = map[type] || (map[type] = []);
                    arr.push(f)
                }
            }
        };
        var noHandlers = [];

        function getHandlers(emitter, type, copy) {
            var arr = emitter._handlers && emitter._handlers[type];
            if (copy) {
                return arr && arr.length > 0 ? arr.slice() : noHandlers
            } else {
                return arr || noHandlers
            }
        }

        var off = TTReader.off = function (emitter, type, f) {
            if (emitter.removeEventListener) {
                emitter.removeEventListener(type, f, false)
            } else {
                if (emitter.detachEvent) {
                    emitter.detachEvent("on" + type, f)
                } else {
                    var handlers = getHandlers(emitter, type, false);
                    if (f) {
                        for (var i = 0; i < handlers.length; ++i) {
                            if (handlers[i] == f) {
                                handlers.splice(i, 1);
                                break
                            }
                        }
                    } else {
                        handlers.splice(0, handlers.length)
                    }
                }
            }
        };
        var signal = TTReader.signal = function (emitter, type) {
            var handlers = getHandlers(emitter, type, true);
            if (!handlers.length) {
                return
            }
            var args = Array.prototype.slice.call(arguments, 2);
            for (var i = 0; i < handlers.length; ++i) {
                handlers[i].apply(null, args)
            }
        };
        var ttreaderEvents = TTReader.Events = {
            BeforeTurning: "before_turning",
            PageIsReady: "page_is_ready",
            ProgressChanged: "progress_changed",
            ReachingTrialEnd: "reaching_trial_end"
        };
        TTReader.version = "1.0.0";
        return TTReader
    }));
    function HeplTips() {
        this.initialize.apply(this, arguments)
    }

    HeplTips.prototype = {
        initialize: function (id) {
            var _this = this;
            this.wrap = typeof id === "string" ? document.getElementById(id) : id;
            this.aLi = this.wrap.getElementsByTagName("li");
            this.next = this.wrap.getElementsByClassName("next");
            this.iShow = 0;
            this._doNext = function () {
                return _this.doNext.apply(_this)
            };
            for (var i = 0; i < this.next.length; i++) {
                this.addEvent(this.next[i], "click", this._doNext)
            }
            $("html").css({height: "100%", overflow: "hidden"});
            if (appUtils.isApp) {
                $("#app_help_tips").removeClass("none")
            } else {
                $("#pc_help_tips").removeClass("none")
            }
            _this.aLi[this.iShow].className = ""
        }, doNext: function () {
            var _this = this;
            _this.iShow++;
            for (var i = 0; i < this.aLi.length; i++) {
                if (i > _this.iShow || i < _this.iShow) {
                    if (_this.iShow == _this.aLi.length) {
                        $("html").css({height: "auto", overflow: "visible"});
                        if (appUtils.isApp) {
                            $("#app_help_tips").addClass("none")
                        } else {
                            $("#pc_help_tips").addClass("none")
                        }
                    }
                    _this.aLi[i].className = "none"
                } else {
                    $("html").css({height: "100%", overflow: "hidden"});
                    _this.aLi[i].className = ""
                }
            }
        }, addEvent: function (oElement, sEventType, fnHandler) {
            return oElement.addEventListener ? oElement.addEventListener(sEventType, fnHandler, false) : oElement.attachEvent("on" + sEventType, fnHandler)
        }
    };
    var listen_start_time;
    var listen_end_time;
    var reader;
    var is_listening = false;
    var listen_able = true;
    var sign_list = [".", "!", "/", "(", ")", "[", "]", ":", ";", '"', "<", ">", "?", "，", "。", "；", "：", "【", "】", "（", "）", "！", "？", "、", "《", "》", "{", "}", "-"];

    function listenReady() {
        var nowPage = reader.flipbook.turn("page");
        var listenText;
        if ($('.page-wrapper[page="' + nowPage + '"]').find(".TTReader-lastpage-body").length > 0) {
            listenText = $('.page-wrapper[page="' + nowPage + '"]').find(".TTReader-lastpage-body").text();
            appUtils.listening(listenText, true)
        } else {
            if (listen_able) {
                $('.page-wrapper[page="' + nowPage + '"]').find(".TTReader-page-body").find("p").each(function (index) {
                    var sign = "。";
                    var reg = /\s+/g;
                    var get_text = $(this).text().replace(reg, "");
                    if (index == 0) {
                        listenText = get_text
                    } else {
                        var last_str = listenText.substr(listenText.length - 1, 1);
                        if (sign_list.indexOf(last_str) > -1) {
                            sign = ""
                        }
                        listenText = listenText + sign + get_text
                    }
                });
                appUtils.listening(listenText, false)
            } else {
                appUtils.listening("。", true)
            }
        }
    }

    var throttLelistenReady = _.throttle(listenReady, 500, {leading: false});
    listenNext = function () {
        if (!reader.flipbook) {
            return
        }
        reader.flipbook.turn("next");
        throttLelistenReady()
    };
    listenStatus = function () {
        is_listening = false;
        var nowDate = new Date();
        listen_end_time = nowDate.getTime();
        var time = listen_end_time - listen_start_time;
        set_listen_time(time)
    };
    $(function () {
        store.remove("reading");
        function preventBodyTouchMove(event) {
            event.preventDefault()
        }

        function GetQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2])
            }
            return null
        }

        document.body.addEventListener("touchmove", preventBodyTouchMove, false);
       /* document.getElementById("MenuPanel").addEventListener("touchmove", function (event) {
            event.stopPropagation()
        }, false);*/
        var ios = /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent);
        var isSkip = GetQueryString("isSkip");
        var params = window.location.search, uri = new Uri(params);
        var search_key = uri.getQueryParamValue("q");
        var authenticated = Cookies.getJSON("authenticated");
        var userId = authenticated || "anyone";
        var now_progress = 0, book_progress = 0, contentLen = 0, last_progress, contentData, book_slug, space = userId,
            progress_key;
        var data_url = $("#dataurl").attr("href");
        var progress_url = $("#progessUrl").attr("href");
        var bookshelf_url = $("#bookshelf_url").attr("href");
        var login_url = $("#login_url").attr("href");
        var vip_url = $("#vip_url").attr("href");
        var trial = false, trial_proportion = 10;
        var collect = $("#collect-btn");

        var seed = "userId:" + userId + ":seed";
        seed = CryptoJS.SHA256(seed).toString(CryptoJS.enc.HEX);
        var key = seed.substr(0, 24);
        var iv = seed.substr(seed.length - 16);
        book_slug = progress_key = result.id;
        var data = CryptoJS.AES.decrypt(result.data, CryptoJS.enc.Utf8.parse(key), {
            mode: CryptoJS.mode.CFB,
            iv: CryptoJS.enc.Utf8.parse(iv),
            padding: CryptoJS.pad.NoPadding
        }).toString(CryptoJS.enc.Utf8);
        if (data.length > 0) {
            data = data.substr(0, data.length - data.charCodeAt(data.length - 1))
        }
        contentData = $.parseJSON(data);
        contentData.id = result.id;
        contentData.title = result.title;
        contentLen = contentData.text.length;
        console.log(result.title);

        /*$.post('/index/api_save',{'id':id,'data':data},function(res){
            window.location.href = '/index/tiao?id='+(id+1)
        },'json');*/

       $("#neirong_json").val(data);
// console.log(data);
return;


        function setUrl(url) {
            var curUri = new Uri(window.location.href);
            var targetUri = new Uri(url).setProtocol(curUri.protocol()).setHost(curUri.host()).setPort(curUri.port());
            return targetUri
        }

        function postProgressToServer(progress) {
            if (collect.hasClass("collectActived")) {
                PubFuncs.zk_ajaxPostRequest(progress_url, {value: progress}, function (data) {
                    if (data.success) {
                        if (Progress.get(space, progress_key)) {
                            progress = Progress.get(space, progress_key).progress
                        }
                        Progress.set(space, progress_key, {progress: progress, modified_time: data.modified_time})
                    } else {
                        layer.msg("更新进度失败，请重试", {time: 1000})
                    }
                }, function (error) {
                    layer.msg("操作失败，请检查网络", {time: 1000})
                })
            }
        }

        function TipsForFirst() {
            if (appUtils.isApp) {
                var help_app = $("#app_help_tips").attr("data-page");
                if (help_app && !store.get(help_app)) {
                    var help_tips_version = $("#app_help_tips").attr("data-version");
                    if (help_tips_version == "1.0") {
                        store.set(help_app, true);
                        new HeplTips("app_help_tips")
                    }
                }
            } else {
                var help_pc = $("#pc_help_tips").attr("data-page");
                if (help_pc && !store.get(help_pc)) {
                    var help_tips_version = $("#pc_help_tips").attr("data-version");
                    if (help_tips_version == "1.0") {
                        store.set(help_pc, true);
                        new HeplTips("pc_help_tips")
                    }
                }
            }
        }

        function updatePercent(value) {
            value = Math.max(0, Math.min(100, value));
            $("#sliderIndicator").text(parseFloat(value).toFixed(1) + "%");
            $(".slider-h").width(value + "%")
        }

        function updateSlider(offset, old_offset) {
            var value = offset * 100 / contentLen;
            if (trial) {
                value = value > trial_proportion ? trial_proportion : value
            }
            var curr_progress = Progress.get(space, progress_key);
            var modified_time = curr_progress && curr_progress.modified_time ? curr_progress.modified_time : "";
            Progress.set(space, progress_key, {progress: offset, modified_time: modified_time});
            $("#slider").val(parseInt(value));
            updatePercent(value);
            if (old_offset) {
                last_progress = old_offset;
                now_progress = offset
            }
        }

        function bookMenuOpen(speed) {
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                $("#bookMenu").removeClass("hidden").addClass("mobile-landscape-bookMenu").css({display: "block"}).animate({bottom: "0"}, speed)
            } else {
                $("#bookMenu").removeClass("hidden").css({display: "block"}).animate({left: "0"}, speed)
            }
        }

        function bookMenuClose(speed) {
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                var offset = $("#bookMenu").outerHeight();
                $("#bookMenu").addClass("hidden").animate({bottom: -offset}, speed, function () {
                    $("#bookMenu").removeClass("mobile-landscape-bookMenu").css({display: "none"})
                })
            } else {
                var offset = $("#bookMenu").outerWidth();
                $("#bookMenu").addClass("hidden").animate({left: -offset}, speed, function () {
                    $("#bookMenu").css({display: "none"})
                })
            }
        }

        function openPanel() {
            $("#bookMenu").addClass("block");
            var offset = $("#MenuPanel").width();
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                $("#MenuPanel").addClass("mobile-landscape-MenuPanel")
            }
            $("#MenuPanel").css({left: -offset, display: "block"}).animate({left: "0"}, 300)
        }

        function closePanel() {
            $("#bookMenu").removeClass("block");
            var offset = $("#MenuPanel").width();
            if ($(this).outerWidth() == $("#content").outerWidth()) {
                bookMenuClose(200)
            }
            $("#MenuPanel").animate({left: -offset}, 300, function () {
                $("#MenuPanel").css({display: "none"});
                $(".MenuList li").each(function () {
                    if ($(this).find("a").attr("name") != "progress") {
                        $(this).removeClass("selected")
                    }
                })
            });
            if (window.innerWidth <= 720 && (window.orientation == 90 || window.orientation == -90)) {
                $("#MenuPanel").removeClass("mobile-landscape-MenuPanel")
            }
        }

        var initReader = _.after(2, function () {
            $("#reader").html("");
            var book_id = contentData.id;
            trial = $("#trial").val() ? true : false;
            if (trial) {
                if (book_progress * trial_proportion > contentLen) {
                    book_progress = parseInt(contentLen / trial_proportion)
                }
            }
            contentData.progress = book_progress;
            reader = TTReader(document.getElementById("reader"), {
                data: contentData,
                ashadow: "#f6f4ec",
                duration: 800
            });
            $(".loading_book").remove();
            TipsForFirst();
            if (appUtils.isApp && appUtils.compVersions("5.2.0") <= 0 && store.get("go_listening")) {
                store.remove("go_listening");
                is_listening = true;
                throttLelistenReady()
            }
            if (trial) {
                var reader_content = document.getElementsByClassName("TTReader");
                var html = '<p class="trial_title">中医智库付费内容</p><p class="trial_tip">查看完整版请开通VIP</p><a href="' + vip_url + '" class="trial_btn out_reader">开通VIP</a>';
                if (!authenticated) {
                    html += '<p class="trial_tail">VIP会员,请<a class="out_reader" href="' + login_url + '">登录</a></p>'
                }
                var trial = document.createElement("div");
                trial.className = "TTReader-trial none";
                trial.innerHTML = html;
                reader_content[0].appendChild(trial);
                $(document).on("click", ".TTReader-trial", function (e) {
                    e.stopPropagation()
                })
            }
            function outReader(selector_url) {
                if (appUtils.isAndroidSupportedReaderEntry) {
                    var culUri = setUrl(selector_url);
                    var url = culUri.addQueryParam("read", true).toString();
                    store.set("reading", true);
                    appUtils.readerEnter(false, url, null)
                }
                if (appUtils.isApp && appUtils.isSupportedNativeBar) {
                    appUtils.setNavBarVisibility(false, function () {
                        window.location = selector_url
                    })
                } else {
                    window.location = selector_url
                }
            }

            $(document).on("click", ".out_reader", function (e) {
                e.preventDefault();
                var me = $(this);
                outReader(me.attr("href"))
            });
            function updateBookPages(delay) {
                var slider_value = $("#slider").val();
                if (trial) {
                    if (slider_value > trial_proportion) {
                        slider_value = trial_proportion
                    }
                }
                reader.updatePages(parseInt(slider_value * contentLen / 100))
            }

            var debounceUpdateBookPages = _.debounce(updateBookPages, 100, {leading: false});
            var book_catalog = reader.doc.catalog.children;
            if (book_catalog) {
                for (var i = 0; i < book_catalog.length; i++) {
                    var catalog1 = (trial && book_catalog[i].o * trial_proportion > contentLen);
                    var className1 = catalog1 ? "title disabled" : "title";
                    var catalog_html = '<h2 name="' + book_catalog[i].o + '" class="' + className1 + '">' + book_catalog[i].t + "</h2>";
                    if (book_catalog[i].children != undefined) {
                        for (var j = 0; j < book_catalog[i].children.length; j++) {
                            var catalog2 = (trial && book_catalog[i].children[j].o * trial_proportion > contentLen);
                            var className2 = catalog2 ? "title disabled" : "title";
                            var subtitle = '<h3 name="' + book_catalog[i].children[j].o + '" class="' + className2 + '">' + book_catalog[i].children[j].t + "</h3>";
                            catalog_html += subtitle
                        }
                    }
                    $("#cont").append(catalog_html)
                }
            }
            updateSlider(book_progress, last_progress);
            TTReader.on(reader, TTReader.Events.ProgressChanged, function (old_progress, progress) {
                updateSlider(progress, old_progress);
                if (authenticated) {
                    postProgressToServer(progress)
                }
                book_progress = progress
            });
            TTReader.on(reader, TTReader.Events.BeforeTurning, function (args) {
                if ($.isTouch && !$("#bookMenu").is(":hidden") && ($(window).width() < 960)) {
                    args.preventDefault = true
                }
            });
            if (trial) {
                var now_page = reader.flipbook.turn("page");
                var page_length = $('.page-wrapper[page="' + now_page + '"]').find(".TTReader-page-body").text().length;
                if (book_progress + page_length > reader.doc.trial) {
                    $(".TTReader-trial").removeClass("none");
                    $(".TTReader-pages").addClass("trial")
                }
                TTReader.on(reader, TTReader.Events.ReachingTrialEnd, function (args) {
                    if (args) {
                        listen_able = true;
                        $(".TTReader-trial").addClass("none");
                        $(".TTReader-pages").removeClass("trial")
                    } else {
                        listen_able = false;
                        $(".TTReader-trial").removeClass("none");
                        $(".TTReader-pages").addClass("trial")
                    }
                })
            }
            if ($.isTouch) {
                var sliderEl = document.getElementById("slider");
                sliderEl.addEventListener("touchend", function (event) {
                    var rect = sliderEl.getBoundingClientRect();
                    var value = (event.changedTouches[0].clientX - rect.left) * 100 / (rect.right - rect.left);
                    $("#slider").val(value);
                    updatePercent(value);
                    debounceUpdateBookPages()
                });
                sliderEl.addEventListener("touchmove", function (event) {
                    var rect = sliderEl.getBoundingClientRect();
                    var value = (event.changedTouches[0].clientX - rect.left) * 100 / (rect.right - rect.left);
                    updatePercent(value)
                }, true)
            } else {
                if (/MSIE \d/.test(navigator.userAgent) && ((document.documentMode || 6) < 10)) {
                    $("#slider").attr("readonly", "true");
                    var b = $(".slider-h span");
                    var halfBWidth = b.width() / 2;
                    var lastPos = -1;
                    $("#slider").on("mousemove", function (event) {
                        if (event.button == 1 && event.offsetX != lastPos) {
                            this.value = (event.offsetX) * 100 / $("#slider").width();
                            updatePercent(this.value);
                            debounceUpdateBookPages();
                            lastPos = event.offsetX
                        }
                    });
                    $("#slider").bind("click", function (event) {
                        this.value = event.offsetX * 100 / $("#slider").width();
                        updatePercent(this.value);
                        debounceUpdateBookPages()
                    })
                } else {
                    $("#slider").on("change", function (event) {
                        debounceUpdateBookPages()
                    });
                    $("#slider").bind("input propertychange", function () {
                        updatePercent($(this).val())
                    })
                }
                $(".TTReader-content").on("mousedown", function (e) {
                    if (!$(".progressForm").is(":hidden")) {
                        $(".progressForm").addClass("none");
                        $("#progress-btn").parent().removeClass("selected")
                    } else {
                        if ($(this).outerWidth() == $("#content").outerWidth()) {
                            if (!$("#bookMenu").is(":hidden")) {
                                bookMenuClose(200)
                            } else {
                                if (e.offsetX > reader.content.clientWidth / 3 && e.offsetX < reader.content.clientWidth * 2 / 3) {
                                    bookMenuOpen(200)
                                }
                            }
                        }
                    }
                })
            }
            function searchBykeyword(keyword) {
                var repat = TTReader.parseKeywordStr(keyword);
                if (null == repat) {
                    return
                }
                var leftOffset = 3;
                var segmentLen = 20;
                var segments = new Array();
                var text = reader.doc.text;
                var end = 0;
                var match;
                while (match = repat.exec(text)) {
                    if ((match.index + match[0].length) <= end) {
                        continue
                    }
                    var _start = Math.max(0, match.index - leftOffset);
                    var segment = text.substr(_start, segmentLen);
                    segment = segment.replace(repat, '<span class="heightLight">$1</span>');
                    var prefix = suffix = "";
                    if (_start > 0) {
                        prefix = "..."
                    }
                    end = _start + segmentLen;
                    if (end < text.length) {
                        suffix = "..."
                    }
                    segments.push({offset: match.index, text: prefix + segment + suffix})
                }
                var resultField = $("#searchResult");
                resultField.html("");
                if (segments.length > 0) {
                    for (var i = 0; i < segments.length; i++) {
                        if (trial && segments[i].offset * trial_proportion > contentLen) {
                            var html = '<p class="title disabled" name="' + segments[i].offset + '">' + segments[i].text + "</p>"
                        } else {
                            var html = '<p class="title" name="' + segments[i].offset + '">' + segments[i].text + "</p>"
                        }
                        resultField.append(html)
                    }
                } else {
                    resultField.html('<div style="text-align:center;line-height:30px;">未找到搜索结果，建议更换搜索词。</div>')
                }
            }

            $("#searchBtn").click(function (e) {
                var keyword = $.trim($("#search-textField").val());
                if (keyword) {
                    searchBykeyword(keyword)
                } else {
                    e.stopPropagation()
                }
            });
            if (search_key != undefined) {
                $("#search-btn").parent().addClass("selected");
                $("#MenuPanel section").addClass("none");
                $("#search").removeClass("none");
                if ($("#MenuPanel").is(":hidden")) {
                    openPanel()
                }
                $("#search-textField").val(search_key);
                searchBykeyword(search_key)
            }
            $(document).on("click", "#cont .title", function () {
                var me = $(this);
                if (!me.hasClass("disabled")) {
                    reader.updatePages(me.attr("name"));
                    if (parseInt($(window).width()) < 960) {
                        closePanel()
                    }
                }
            });
            $(document).on("click", "#searchResult .title", function () {
                var me = $(this);
                if (!me.hasClass("disabled")) {
                    var offset = me.attr("name");
                    var keywords = $.trim($("#search-textField").val());
                    TTReader.on(reader, TTReader.Events.PageIsReady, function (page) {
                        if (!reader.flipbook) {
                            return
                        }
                    });
                    reader.updatePages(offset, keywords);
                    if (parseInt($(window).width()) < 960) {
                        closePanel()
                    }
                }
            });
            var bookShelfCallback = function (res, bookShelfData) {
                if (res.success == true) {
                    if (bookShelfData.operation == "add") {
                        collect.addClass("collectActived");
                        postProgressToServer(book_progress);
                        layer.msg("已加入书架！", {time: 1000})
                    } else {
                        collect.removeClass("collectActived");
                        layer.msg("已移除书架！", {time: 1000})
                    }
                    store.remove("bookShelfData")
                } else {
                    store.set("bookShelfData", bookShelfData);
                    outReader(login_url)
                }
            };
            var bookShelfManage = function (bookShelfData) {
                $.ajax({
                    url: bookshelf_url,
                    type: "POST",
                    data: bookShelfData,
                    headers: {"X-CSRFToken": Cookies.get("csrftoken")},
                    dataType: "json",
                    traditional: true,
                    success: function (data) {
                        bookShelfCallback(data, bookShelfData)
                    },
                    error: function (error) {
                        layer.msg("操作失败，请检查网络", {time: 2000})
                    }
                })
            };
            var bookShelfEvent = $.isTouch ? "touchstart" : "click";
            $("#collect-btn").bind(bookShelfEvent, function () {
                var operation = $(this).hasClass("collectActived") ? "remove" : "add";
                bookShelfManage({operation: operation, book_ids: book_id})
            });
            var store_bookShelfData = store.get("bookShelfData");
            if (store_bookShelfData && authenticated) {
                bookShelfManage(store_bookShelfData)
            }
            $("#to_last_progress").click(function () {
                book_progress = last_progress;
                last_progress = now_progress;
                updateSlider(book_progress, last_progress);
                reader.updatePages(book_progress)
            });
            httpCache.updateCache("libinsight_book_" + book_slug, data_url)
        });
        $("#cont-btn, #search-btn").click(function () {
            var me = $(this);
            $(".MenuList li").removeClass("selected");
            me.parent("li").addClass("selected");
            if (!$(".progressForm").is(":hidden")) {
                $(".progressForm").addClass("none")
            }
            $("#MenuPanel section").addClass("none");
            var name = me.attr("name");
            $("#" + name).removeClass("none");
            if ($("#MenuPanel").is(":hidden")) {
                openPanel()
            }
        });
        $("#goback").click(function () {
            if (appUtils.isAndroidSupportedReaderEntry) {
                appUtils.readerEnter(false, null, null)
            } else {
                if (appUtils.isApp && appUtils.isSupportedNativeBar) {
                    appUtils.setNavBarVisibility(false, function () {
                        window.history.back()
                    })
                } else {
                    window.history.back()
                }
            }
        });
        $("#MenuPanelClose").click(function () {
            if ($("#MenuPanel").is(":visible")) {
                closePanel()
            }
        });
        $("#progress-btn").click(function () {
            $(".MenuList li").removeClass("selected");
            $(this).parent().addClass("selected");
            if ($("#MenuPanel").is(":visible")) {
                closePanel()
            } else {
                if (!$("#bookMenu").is(":hidden")) {
                    bookMenuClose(200)
                }
            }
            $(".progressForm").removeClass("none")
        });
        $("#search-textField").keypress(function (e) {
            if (e.which == 13) {
                $("#searchBtn").click()
            }
        });
        if (!isSkip) {
            var httpCache = HttpCache({name: "user-" + userId, useCacheStore: true});
            httpCache.getJSON(data_url, function (result) {
                var seed = "userId:" + userId + ":seed";
                seed = CryptoJS.SHA256(seed).toString(CryptoJS.enc.HEX);
                var key = seed.substr(0, 24);
                var iv = seed.substr(seed.length - 16);
                book_slug = progress_key = result.id;
                var data = CryptoJS.AES.decrypt(result.data, CryptoJS.enc.Utf8.parse(key), {
                    mode: CryptoJS.mode.CFB,
                    iv: CryptoJS.enc.Utf8.parse(iv),
                    padding: CryptoJS.pad.NoPadding
                }).toString(CryptoJS.enc.Utf8);
                if (data.length > 0) {
                    data = data.substr(0, data.length - data.charCodeAt(data.length - 1))
                }
                contentData = $.parseJSON(data);
                contentData.id = result.id;
                contentData.title = result.title;
                contentLen = contentData.text.length;
                initReader();
                local_progress = Progress.get(space, progress_key);
                if (local_progress) {
                    book_progress = local_progress.progress
                }
                if (!search_key) {
                    if (authenticated) {
                        $.ajax({
                            url: progress_url, dataType: "json", type: "GET", success: function (data) {
                                if (data.success) {
                                    var serverTime = data.modified_time;
                                    if (local_progress) {
                                        var localTime = local_progress.modified_time;
                                        if (serverTime > localTime) {
                                            var update_progress;
                                            update_progress = layer.confirm("同步到您云端的阅读进度", {
                                                skin: "update_progress",
                                                title: false,
                                                closeBtn: 0,
                                                area: ["210px", "110px"],
                                                btn: ["取消", "跳转"]
                                            }, function (index, layero) {
                                                postProgressToServer(local_progress.progress);
                                                layer.close(update_progress)
                                            }, function (index) {
                                                book_progress = data.progress;
                                                last_progress = local_progress.progress;
                                                Progress.set(space, progress_key, {
                                                    progress: book_progress,
                                                    modified_time: serverTime
                                                });
                                                layer.close(update_progress);
                                                updateSlider(book_progress, last_progress);
                                                reader.updatePages(book_progress)
                                            })
                                        }
                                    }
                                    initReader()
                                } else {
                                    layer.msg("获取云端进度失败", {time: 1000})
                                }
                            }, error: function (error) {
                                layer.msg("获取云端进度失败", {time: 1000})
                            }
                        })
                    } else {
                        initReader()
                    }
                } else {
                    initReader()
                }
            })
        }
        if (appUtils.isApp) {
            $("#listen-btn").click(function () {
                if ($("#MenuPanel").is(":visible")) {
                    closePanel()
                }
                bookMenuClose(200);
                is_listening = true;
                var nowDate = new Date();
                listen_start_time = nowDate.getTime();
                throttLelistenReady()
            })
        }
    })
}).call(this);
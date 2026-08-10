<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Kocaeli'nin tüm ilçeleri ve gerçek mahalleleri (tr.wikipedia.org ilçe sayfalarından).
     */
    public function run(): void
    {
        $districts = [
            'İzmit' => [
                '28 Haziran', 'Akarca', 'Akçakoca', 'Akmeşe Cumhuriyet', 'Akpınar', 'Alikahya Atatürk',
                'Alikahya Cumhuriyet', 'Alikahya Fatih', 'Ambarcı', 'Arızlı', 'Arpalıkihsaniye', 'Atatürk',
                'Ayazma', 'Bağlıca', 'Balören', 'Bayraktar', 'Bekirdere', 'Biberoğlu', 'Böğürgen', 'Bulduk',
                'Çağırgan', 'Çavuşoğlu', 'Çayırköy', 'Cedit', 'Cumhuriyet', 'Çubuklubala', 'Çubukluosmaniye',
                'Çukurbağ', 'Dağköy', 'Doğan', 'Durhasan', 'Düğmeciler', 'Emirhan', 'Erenler', 'Eseler',
                'Fatih', 'Fethiye', 'Fevzi Çakmak', 'Gedikli', 'Gökçeören', 'Gülbahçe Kadriye', 'Gültepe',
                'Gündoğdu', 'Güvercinlik', 'Hacı Hasan', 'Hacı Hızır', 'Hakaniye', 'Hasancıklar', 'Hatip',
                'Kabaoğlu', 'Kadıköy', 'Karaabdülbaki', 'Karabaş', 'Karadenizliler', 'Kaynarca', 'Kemalpaşa',
                'Kısalar', 'Kocatepe', 'Kozluca', 'Kozluk', 'Körfez', 'Kulfallı', 'Kulmahmut', 'Kurtdere',
                'Kuruçeşme Fatih', 'Malta', 'Mecidiye', 'Mehmet Ali Paşa', 'Merkez', 'Nebihoca', 'Orhan',
                'Orhaniye', 'Ömerağa', 'Ortaburun', 'Sanayi', 'Sapakpınar', 'Sarışeyh', 'Sekbanlı', 'Sepetçi',
                'Serdar', 'Şirintepe', 'Sultaniye', 'Süleymaniye', 'Süverler', 'Şahinler', 'Tavşantepe',
                'Tepecik', 'Tepeköy', 'Terzibayırı', 'Topçular', 'Turgut', 'Tüysüzler', 'Veliahmet',
                'Yahya Kaptan', '42 Evler', 'Yassıbağ', 'Yenice', 'Yenidoğan', 'Yenimahalle', 'Yenişehir',
                'Yeşilova', 'Zabıtan', 'Zeytinburnu',
            ],
            'Gebze' => [
                'Adem Yavuz', 'Arapçeşme', 'Barış', 'Beylikbağı', 'Cumhuriyet', 'Gaziler', 'Güzeller',
                'Hacıhalil', 'Hürriyet', 'İnönü', 'İstasyon', 'Kirazpınar', 'Köşklüçeşme', 'Mevlana',
                'Mimar Sinan', 'Mustafapaşa', 'Osman Yılmaz', 'Sultan Orhan', 'Tatlıkuyu', 'Ulus',
                'Yavuz Selim', 'Yenikent', 'Ahatlı', 'Balçık', 'Cumaköy', 'Denizli', 'Duraklı', 'Elbizli',
                'Eskihisar', 'Hatipler', 'Kadıllı', 'Kargalı', 'Mollafenari', 'Muallimköy', 'Mudarlı',
                'Ovacık', 'Pelitli', 'Tavşanlı', 'Tepemanayır', 'Yağcılar',
            ],
            'Darıca' => [
                'Abdi İpekçi', 'Bağlarbaşı', 'Bayramoğlu', 'Cami', 'Emek', 'Fevziçakmak', 'Kazımkarabekir',
                'Nenehatun', 'Osmangazi', 'Piri Reis', 'Sırasöğütler', 'Yalı', 'Yenimahalle', 'Zincirlikuyu',
            ],
            'Çayırova' => [
                'Akse', 'Atatürk', 'Cumhuriyet', 'Çayırova', 'Emek', 'İnönü', 'Özgürlük', 'Şekerpınar',
                'Yenimahalle',
            ],
            'Derince' => [
                'Dumlupınar', 'Deniz', 'Çenedağ', 'Sırrıpaşa', 'Çınarlı', 'Yenikent', 'Mersincik',
                'Yavuz Sultan', 'İbnisina', 'Fatih', 'Karagöllü', 'Terziler', 'Çavuşlu', 'Tahtalı', 'Toylar',
                'Geredeli', 'Kaşıkçı',
            ],
            'Dilovası' => [
                'Cumhuriyet', 'Diliskelesi', 'Fatih', 'Kayapınar', 'Mimar Sinan', 'Orhangazi', 'Turgut Özal',
                'Çerkeşli', 'Demirciler', 'Köseler', 'Tavşancıl', 'Tepecik',
            ],
            'Gölcük' => [
                'Atatürk', 'Cumhuriyet', 'Çiftlik', 'Değirmendere Merkez', 'Değirmendere Yalı', 'Denizevler',
                'Donanma', 'Dumlupınar', 'Düzağaç', 'Halıdere Yalı', 'Halıdere Yeni', 'Hisareyn Merkez',
                'İhsaniye Merkez', 'İpek Yolu', 'Karaköprü', 'Kavaklı', 'Körfez', 'Merkez', 'Piyalepaşa',
                'Şehitler', 'Şirinköy', 'Topçular', 'Ulaşlı Yalı', 'Ulaşlı Yavuz Sultan Selim',
                'Yazlık Merkez', 'Yazlık Yeni', 'Yenimahalle', 'Yukarı', 'Yunus Emre', 'Yüzbaşılar',
                'Ayvazpınarı', 'Eskiferhadiye', 'Ferhadiye', 'Hamidiye', 'Hasaneyn', 'İcadiye', 'İrşadiye',
                'Lütfiye', 'Mamuriye', 'Mesruriye', 'Nimetiye', 'Nüzhetiye', 'Örcün', 'Panayır', 'Saraylı',
                'Selimiye', 'Siyretiye', 'Sofular', 'Şevketiye', 'Ümmiye',
            ],
            'Kandıra' => [
                'Akdurak', 'Aydınlık', 'Çarşı', 'Orhan', 'Ağaçağıl', 'Ahmethacılar', 'Akbal', 'Akçabeyli',
                'Akçakese', 'Akçaova', 'Akıncı', 'Alaybey', 'Alefli', 'Antaplı', 'Avdan', 'Babaköy', 'Babalı',
                'Bağırganlı', 'Balaban', 'Balcı', 'Ballar', 'Beyce', 'Beylerbeyi', 'Bolu', 'Bozburun',
                'Cebeci', 'Çakırcaali', 'Çakmaklar', 'Çalca', 'Çalköy', 'Çalyer', 'Çamkonak', 'Çerçili',
                'Dalca', 'Deliveli', 'Doğancılı', 'Döngelli', 'Duraklı', 'Eğercili', 'Elmacık', 'Esentepe',
                'Ferizli', 'Gebeşler', 'Goncaaydın', 'Hacılar', 'Hacımazlı', 'Hacışeyh', 'Hediyeli',
                'Hıdırlar', 'Hüdaverdiler', 'İncecik', 'Kabaağaç', 'Kanatlar', 'Karaağaç', 'Karadivan',
                'Karlı', 'Kaymaz', 'Kaymaz Erikli', 'Kefken', 'Kıncıllı', 'Kırkarmut', 'Kızılcapınar',
                'Kocakaymas', 'Kubuzcu', 'Kurtyeri', 'Lokmanlı', 'Mancarlar', 'Merkez Erikli',
                'Mülküşehsuvar', 'Nasuhlar', 'Ömerli', 'Özbey', 'Pelitpınarı', 'Pınardüzü', 'Pınarlı',
                'Pirceler', 'Safalı', 'Sarıahmetler', 'Sarıcaali', 'Sarıgazi', 'Sarnıçlar', 'Selametli',
                'Selimköy', 'Sepetçi', 'Seyitaliler', 'Sinanlıbilalli', 'Sucuali', 'Süllü', 'Şerefsungur',
                'Tatarahmet', 'Teksen', 'Terziler', 'Topluca', 'Üğümce', 'Yağcılar', 'Yusufça',
            ],
            'Karamürsel' => [
                '4 Temmuz', 'Kayacık', 'Akçat', 'Akpınar', 'Avcıköy', 'Çamçukur', 'Çamdibi', 'Dereköy',
                'Ereğli', 'Fulacık', 'Hayriye', 'İhsaniye', 'İnebeyli', 'Kadriye', 'Karaahmetli', 'Karapınar',
                'Kızderbent', 'Oluklu', 'Osmaniye', 'Pazarköy', 'Safiye', 'Semetler', 'Senaiye', 'Suludere',
                'Tahtalı', 'Tepeköy', 'Yalakdere',
            ],
            'Kartepe' => [
                'Ataevler', 'Dumlupınar', 'Emekevler', 'Ertuğrul Gazi', 'Fatih Sultan Mehmet', 'İstasyon',
                'Köseköy', 'Acısu', 'Arslanbey', 'Ataşehir', 'Balaban', 'Çepni', 'Derbent', 'Eşme',
                'Eşmeahmediye', 'Havluburun', 'İbrikdere', 'Karatepe', 'Ketenciler', 'Maşukiye', 'Nusretiye',
                'Pazarçayırı', 'Rahmiye', 'Sarımeşe', 'Serinlik', 'Suadiye', 'Sultaniye', 'Şevkatiye',
                'Şirin Sulhiye', 'Tepe Tarla', 'Uzunbey', 'Uzunçiftlik', 'Uzuntarla',
            ],
            'Körfez' => [
                '17 Ağustos', 'Agah Ateş', 'Atalar', 'Barbaros', 'Cumhuriyet', 'Çamlıtepe', 'Esentepe',
                'Fatih', 'Güney', 'Hacı Akif', 'Hacı Osman', 'İlimtepe', 'Kışladüzü', 'Kirazlıyalı', 'Kuzey',
                'Mimar Sinan', 'Şirinyalı', 'Yavuz Sultan Selim', 'Yeniyalı', 'Yukarı Hereke', 'Alihocalar',
                'Belen', 'Cuma', 'Çıraklı', 'Dere', 'Dikenli', 'Elmacık', 'Himmetli', 'Kalburcu',
                'Karayakuplu', 'Kutluca', 'Naip', 'Osmanlı', 'Sevindikli', 'Sipahiler', 'Şemsettin',
            ],
            'Başiskele' => [
                'Aksığın', 'Altınkent', 'Atakent', 'Aydınkent', 'Barbaros', 'Camidüzü', 'Damlar', 'Doğantepe',
                'Döngel', 'Fatih', 'Havuzlubahçe', 'Karadenizliler', 'Karşıyaka', 'Kazandere', 'Kılıçarslan',
                'Körfez', 'Kullar Tepecik', 'Kullar Yakacık', 'Mahmutpaşa', 'Mehmetağa', 'Ovacık', 'Paşadağ',
                'Sahil', 'Sepetlipınar', 'Serdar', 'Serindere', 'Servetiye Cami', 'Servetiye Karşı', 'Seymen',
                'Şehitekrem', 'Tepecik', 'Vezirçiftliği', 'Yaylacık', 'Yeniköy Merkez', 'Yeşilkent',
                'Yeşilyurt', 'Yuvacık Yakacık',
            ],
        ];

        foreach ($districts as $district => $neighborhoods) {
            foreach ($neighborhoods as $neighborhood) {
                Location::firstOrCreate([
                    'province' => 'Kocaeli',
                    'district' => $district,
                    'neighborhood' => $neighborhood,
                ]);
            }
        }
    }
}

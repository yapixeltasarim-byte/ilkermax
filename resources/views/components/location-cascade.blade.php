@props(['locations'])

<div
    x-data="{
        locations: {{ Illuminate\Support\Js::from($locations->map(fn ($location) => [
            'province' => $location->province,
            'district' => $location->district,
            'neighborhood' => $location->neighborhood,
        ])) }},
        province: {{ Illuminate\Support\Js::from(request('province', '')) }},
        district: {{ Illuminate\Support\Js::from(request('district', '')) }},
        neighborhood: {{ Illuminate\Support\Js::from(request('neighborhood', '')) }},
        get provinces() {
            return [...new Set(this.locations.map(l => l.province))];
        },
        get districts() {
            return [...new Set(this.locations.filter(l => !this.province || l.province === this.province).map(l => l.district))];
        },
        get neighborhoods() {
            return [...new Set(this.locations.filter(l => (!this.province || l.province === this.province) && (!this.district || l.district === this.district) && l.neighborhood).map(l => l.neighborhood))];
        },
    }"
    class="contents"
>
    <select name="province" x-model="province" @change="district = ''; neighborhood = ''" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
        <option value="">Tüm İller</option>
        <template x-for="option in provinces" :key="option">
            <option :value="option" x-text="option" :selected="option === province"></option>
        </template>
    </select>

    <select name="district" x-model="district" @change="neighborhood = ''" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
        <option value="">Tüm İlçeler</option>
        <template x-for="option in districts" :key="option">
            <option :value="option" x-text="option" :selected="option === district"></option>
        </template>
    </select>

    <select name="neighborhood" x-model="neighborhood" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
        <option value="">Tüm Mahalleler</option>
        <template x-for="option in neighborhoods" :key="option">
            <option :value="option" x-text="option" :selected="option === neighborhood"></option>
        </template>
    </select>
</div>

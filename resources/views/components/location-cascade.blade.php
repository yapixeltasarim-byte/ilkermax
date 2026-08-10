@props(['locations'])

@php
    $provinceOptions = $locations->pluck('province')->unique()->values();
    $singleProvince = $provinceOptions->count() === 1 ? $provinceOptions->first() : null;
@endphp

<div
    x-data="{
        locations: {{ Illuminate\Support\Js::from($locations->map(fn ($location) => [
            'province' => $location->province,
            'district' => $location->district,
            'neighborhood' => $location->neighborhood,
        ])) }},
        province: {{ Illuminate\Support\Js::from(request('province', $singleProvince ?? '')) }},
        district: {{ Illuminate\Support\Js::from(request('district', '')) }},
        neighborhood: {{ Illuminate\Support\Js::from(request('neighborhood', '')) }},
        get provinces() {
            return [...new Set(this.locations.map(l => l.province))];
        },
        get districts() {
            return [...new Set(this.locations.filter(l => !this.province || l.province === this.province).map(l => l.district))];
        },
        get neighborhoods() {
            if (!this.district) {
                return [];
            }

            return [...new Set(this.locations.filter(l => (!this.province || l.province === this.province) && l.district === this.district && l.neighborhood).map(l => l.neighborhood))];
        },
    }"
    class="contents"
>
    @if ($singleProvince)
        <input type="hidden" name="province" :value="province">
    @else
        <select name="province" x-model="province" @change="district = ''; neighborhood = ''" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy">
            <option value="">Tüm İller</option>
            <template x-for="option in provinces" :key="option">
                <option :value="option" x-text="option" :selected="option === province"></option>
            </template>
        </select>
    @endif

    <select name="district" x-model="district" @change="neighborhood = ''" :disabled="!province" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400">
        <option value="" x-text="province ? 'Tüm İlçeler' : 'Önce il seçin'"></option>
        <template x-for="option in districts" :key="option">
            <option :value="option" x-text="option" :selected="option === district"></option>
        </template>
    </select>

    <select name="neighborhood" x-model="neighborhood" :disabled="!district" class="w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-brand-navy focus:outline-none focus:ring-1 focus:ring-brand-navy disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400">
        <option value="" x-text="district ? 'Tüm Mahalleler' : 'Önce ilçe seçin'"></option>
        <template x-for="option in neighborhoods" :key="option">
            <option :value="option" x-text="option" :selected="option === neighborhood"></option>
        </template>
    </select>
</div>

@php
    $orbStats = $stats ?? [];
    $orbSize = ($size ?? '') === 'sm' ? ' orb-wrap--sm' : '';
@endphp

<div class="orb-wrap{{ $orbSize }}">
    <div class="orb-aura"></div>

    <div class="orb-spin">
        <div class="orb-card" @if (!empty($tilt)) data-tilt data-tilt-max="10" @endif>
            <span class="orb-ring" aria-hidden="true"><span class="orb-ring-spin"></span></span>
            <div class="orb-inner">
                <div class="orb-logo-plate">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SDN 2 Petungsewu" class="orb-logo">
                </div>
                <h3 class="orb-name">SDN 2<br>Petungsewu</h3>
                <p class="orb-loc">Wagir • Malang</p>
                <div class="orb-stars">
                    @foreach (range(1, 5) as $i)
                        <span></span>
                    @endforeach
                </div>
                @if (count($orbStats))
                    <div class="orb-stats">
                        @foreach ($orbStats as $stat)
                            <span class="orb-stat">
                                <span>{{ $stat['icon'] ?? '•' }}</span>
                                <b>{{ $stat['value'] }}</b>
                                @if (!empty($stat['label'])) {{ $stat['label'] }} @endif
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="orb-back" aria-hidden="true">
            <span class="orb-ring"><span class="orb-ring-spin"></span></span>
            <div>
                <span class="orb-back-mark"></span>
                <p class="orb-back-title">Web Edukasi</p>
                <p class="orb-back-sub">SDN 2 Petungsewu</p>
            </div>
        </div>
    </div>
</div>

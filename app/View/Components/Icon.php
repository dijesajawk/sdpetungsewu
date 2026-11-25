<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{
    public string $compiledClass;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $name = null,
        ?string $class = null,
        ?string $size = null,
        ?string $color = null,
    ) {
        $this->compiledClass = self::mergeClasses('ico', $class, $size, $color);
    }

    public function render(): View|Closure|string
    {
        return view('components.icon');
    }

    /**
     * Collection of inline SVG icon paths keyed by name.
     * Kept on the class so the Blade view can reuse the same registry
     * without a separate hardcoded array.
     */
    public static function icons(): array
    {
        return [
            // ---------- LINE / DOCS ----------
            'book-open' => '<path d="M2 4h6a4 4 0 0 1 4 4v12a3 3 0 0 0-3-3H2z"/><path d="M22 4h-6a4 4 0 0 0-4 4v12a3 3 0 0 1 3-3h7z"/>',
            'clipboard-list' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M8 11h8M8 15h5"/>',
            'file-text' => '<path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z"/><path d="M14 2v5h5"/><path d="M9 13h6M9 17h6"/>',

            // ---------- STATS / NAV ----------
            'bar-chart' => '<path d="M3 21h18"/><rect x="5" y="11" width="3" height="6" rx="1"/><rect x="10.5" y="7" width="3" height="10" rx="1"/><rect x="16" y="13" width="3" height="4" rx="1"/>',
            'home' => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/>',
            'layers' => '<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/>',
            'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4" fill="currentColor" stroke="none"/>',
            'trending-up' => '<path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
            'activity' => '<path d="M3 12h4l3-7 4 14 3-7h4"/>',

            // ---------- NAV CHEVRONS / LINKS ----------
            'arrow-right' => '<path d="M4 12h15"/><path d="m13 6 6 6-6 6"/>',
            'arrow-left' => '<path d="M20 12H5"/><path d="m11 6-6 6 6 6"/>',
            'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
            'chevron-up' => '<path d="m18 15-6-6-6 6"/>',
            'chevron-left' => '<path d="m15 6-6 6 6 6"/>',
            'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
            'external-link' => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><path d="M10 13h4"/>',

            // ---------- UI / ACTIONS ----------
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'play' => '<path d="M7 4.5v15l13-7.5z"/>',
            'trash' => '<path d="M4 7h16"/><path d="M9 7V4h6v3"/><path d="M6 7l1 14h10l1-14"/><path d="M10 11v6M14 11v6"/>',
            'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
            'zoom-in' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path d="M11 8v6M8 11h6"/>',
            'zoom-out' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path d="M8 11h6"/>',
            'refresh' => '<path d="M21 12a9 9 0 1 1-2.6-6.4"/><path d="M21 4v5h-5"/>',
            'upload' => '<path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 17v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2"/>',
            'download' => '<path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M5 11v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-6"/>',
            'save' => '<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M8 3v6h8V3M8 21v-7h8v7"/>',
            'edit' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/><path d="M16 4l-8-4 8 4z"/>',
            'share' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.5 10-3 2m0 0 3 2m-3-2L15 15"/>',
            'shuffle' => '<path d="M3 7h3l11 10h4"/><path d="M3 17h3l11-10h4"/><path d="m17 3 4 4-4 4M17 13l4 4-4 4"/>',
            'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .34 1.8 7 7 0 0 0 9.1-4.1A1.65 1.65 0 0 0 17 12.1a1.65 1.65 0 0 0-1.8-1.2 9 9 0 0 0-5.4 1.3A6.34 6.34 0 0 1 12 21c-3.87 0-7-3.13-7-7 0-1.39.29-2.72.83-3.95A6.34 6.34 0 0 1 2 12a7 7 0 1 0 14 0c0 1.63-.4 3.19-1.15 4.55"/>',

            // ---------- PEOPLE ----------
            'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
            'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 21a6.5 6.5 0 0 1 13 0"/><path d="M16 5.5a3.5 3.5 0 0 1 0 6.9"/><path d="M18 21a6.6 6.6 0 0 0-2-4.7"/>',
            'user-check' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/><path d="M11 14.5l4 4 2-4"/>',

            // ---------- NOTIFICATIONS / COMMUNITY ----------
            'bell' => '<path d="M18 9a6 6 0 1 0-12 0c0 6-2 7-2 7h16s-2-1-2-7"/><path d="M10.5 20a2 2 0 0 0 3 0"/>',
            'inbox' => '<path d="M3 12h5l1.5 3h5L16 12h5"/><path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>',
            'mail' => '<rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 6 10-6"/>',

            // ---------- AUTH / SECURITY ----------
            'key' => '<circle cx="7.5" cy="15.5" r="4"/><path d="m10.5 12.5 8-8M15 4l3 3M18 7l2 2"/>',
            'lock' => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
            'shield-check' => '<path d="M12 22s8-3.5 8-10V5l-8-3-8 3v7c0 6.5 8 10 8 10z"/><path d="m8.5 12 2.5 2.5 4.5-4.5"/>',
            'shield-x' => '<path d="M12 22s8-3.5 8-10V5l-8-3-8 3v7c0 6.5 8 10 8 10z"/><path d="M9 9l4 4M15 9l-4 4"/>',

            // ---------- STATUS / METRICS ----------
            'check' => '<path d="m4.5 12.5 5 5 10-11"/>',
            'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>',
            'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
            'x-circle' => '<circle cx="12" cy="12" r="9"/><path d="m9 9 6 6M15 9l-6 6"/>',
            'dot' => '<circle cx="12" cy="12" r="5" fill="currentColor" stroke="none"/>',
            'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.5l3.5 2"/>',

            // ---------- MISC SMALL SHAPES ----------
            'star' => '<path d="m12 3.5 2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.8-5.2 2.8 1-5.8L3.5 9.7l5.9-.9z"/>',
            'award' => '<circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8"/>',
            'tag' => '<path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0l-8.2-8.2a2 2 0 0 1-.6-1.4V4a2 2 0 0 1 2-2h7a2 2 0 0 1 1.4.6l8.2 8.2a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/>',
            'bookmark' => '<path d="M6 3h12a1 1 0 0 1 1 1v17l-7-4-7 4V4a1 1 0 0 1 1-1z"/>',
            'heart' => '<path d="M12 20s-8-4.5-8-10a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.5-8 10-8 10z"/>',
            'smile' => '<circle cx="12" cy="12" r="9"/><path d="M8.5 14.5a4.5 4.5 0 0 0 7 0"/><path d="M9 9.5h.01M15 9.5h.01"/>',
            'alert-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 9v4M12 15.5v.5"/>',
            'alert-triangle' => '<path d="M12 2 2 20h20L12 2z"/><path d="M12 9v5M12 17h.01"/>',
            'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 15v-4M12 8h.01"/>',
            'help-circle' => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 0 1 4.5 1.2c0 1.8-2.5 2-2.5 3.8"/><path d="M12 17.5h.01"/>',
            'eye' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"/><circle cx="12" cy="12" r="2.8"/>',
            'image' => '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 16-5-5-9 9"/>',
            'move' => '<path d="M2 4v6a8 8 0 0 0 16 0V4"/><path d="M2 12v6a8 8 0 0 0 16 0V12"/><path d="M2 20v2h16v-2"/>',

            // ---------- BRAND / EDUCATION ----------
            'school' => '<path d="M3 10.5 12 4l9 6.5"/><path d="M5 10v10h14V10"/><path d="M9.5 20v-6h5v6"/>',
            'graduation-cap' => '<path d="m2 8 10-4 10 4-10 4z"/><path d="M6 10v4c0 1.7 2.7 3 6 3s6-1.3 6-3v-4"/><path d="M22 8v5"/>',
            'bookmark' => '<path d="M6 3h12a1 1 0 0 1 1 1v17l-7-4-7 4V4a1 1 0 0 1 1-1z"/>',
            'rocket' => '<path d="M5 15c-1.5 1.5-2 5-2 5s3.5-.5 5-2"/><path d="M14.5 4.5C17 2 21 3 21 3s1 4-1.5 6.5L14 15l-5-5z"/><circle cx="15.5" cy="8.5" r="1.3"/>',
            'zap' => '<path d="M13 2 4 14h6l-1 8 9-12h-6z"/>',
            'sparkles' => '<path d="M12 3l1.8 4.7L18.5 9.5 13.8 11.3 12 16l-1.8-4.7L5.5 9.5l4.7-1.8z"/><path d="M19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9z"/>',
            'trophy' => '<path d="M8 3h8v5a4 4 0 0 1-8 0z"/><path d="M8 5H5v2a3 3 0 0 0 3 3M16 5h3v2a3 3 0 0 1-3 3"/><path d="M12 12v5M9 21h6M10 21v-4h4v4"/>',
            'flask' => '<path d="M9 3h6M10 3v6l-5.5 9a2 2 0 0 0 1.7 3h11.6a2 2 0 0 0 1.7-3L14 9V3"/><path d="M7 15h10"/>',
            'compass' => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5-5 2 2-5z"/>',
            'lightbulb' => '<path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2h5c0-.8.4-1.5 1-2A6 6 0 0 0 12 3z"/>',
            'pencil' => '<path d="M4 20h4l10-10a2.8 2.8 0 0 0-4-4L4 16z"/><path d="m13.5 6.5 4 4"/>',
            'link' => '<path d="M9 15 15 9"/><path d="M10.5 6.5 12 5a4 4 0 0 1 6 6l-1.5 1.5"/><path d="M13.5 17.5 12 19a4 4 0 0 1-6-6l1.5-1.5"/>',
            'camera' => '<path d="M4 8h3l2-3h6l2 3h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.5"/>',
        ];
    }

    /**
     * Merge icon utility classes in priority order:
     * explicit class > color > size > base .ico
     */
    private static function mergeClasses(string $base, ?string $class, ?string $size, ?string $color): string
    {
        $out = [$base];

        if ($color) {
            $out[] = $color;
        }

        if ($size === 'sm') {
            $out[] = 'ico-sm';
        } elseif ($size === 'lg') {
            $out[] = 'ico-lg';
        }

        if ($class) {
            $out[] = $class;
        }

        return implode(' ', array_values(array_unique($out)));
    }
}

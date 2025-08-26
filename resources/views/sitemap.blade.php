<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach ($datas as $data)
    <url>
      @if($data->type == 'page')
        <loc>{{ url('/') }}/{{ strtolower($data->slug) }}</loc>
        <lastmod>{{ $data->created_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
      @else
        <loc>{{ url('/') }}/products/{{ $data->slug_url }}</loc>
        <lastmod>{{ $data->created_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
      @endif        
    </url>
  @endforeach
</urlset>
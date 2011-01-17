#!/usr/local/bin/perl -w
use strict;
use warnings;
use utf8;
use XML::Simple ();
use Encode;
use WWW::MobileCarrierJP::DoCoMo::Display;

do_task(@ARGV);

sub do_task {
    my $dat = WWW::MobileCarrierJP::DoCoMo::Display->scrape;
    my %map;
    for my $phone (@$dat) {
        $map{ uc $phone->{model} } = +{
            width  => $phone->{width},
            height => $phone->{height},
            color  => $phone->{is_color},
            depth  => $phone->{depth},
        };
    }
    output_code( \%map );
}

sub output_code {
    my ($map) = @_;
    my $xml = XML::Simple->new;
    printf <<'TEMPLATE', $xml->XMLout($map);
<?xml version="1.0" ?>
%s
TEMPLATE
}


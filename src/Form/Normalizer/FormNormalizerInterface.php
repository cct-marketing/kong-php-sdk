<?php

declare(strict_types=1);

namespace CCT\Kong\Form\Normalizer;

interface FormNormalizerInterface
{
    /**
     * Normalizes the form data to acceptable format for Kong API.
     *
     * @param array $formData
     *
     * @return array
     */
    public function normalize($formData = []) : array;
}
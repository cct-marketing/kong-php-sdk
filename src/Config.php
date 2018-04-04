<?php

namespace CCT\Kong;

use CCT\Component\Collections\ParameterCollection;

class Config extends ParameterCollection
{
    const ENDPOINT = 'kong.config.endpoint';

    const URI_PREFIX = 'kong.config.uri_prefix';

    const DEBUG = 'kong.config.debug';

    const METADATA_DIRS = 'kong.config.metadata_dirs';

    const EVENT_SUBSCRIBERS = 'kong.config.event_subscribers';

    const SERIALIZATION_HANDLERS = 'kong.config.serialization_handlers';

    const OBJECT_CONSTRUCTOR = 'kong.config.object_constructor';

    const RESPONSE_TRANSFORMERS = 'kong.config.response_transformers';

    const USE_DEFAULT_RESPONSE_TRANSFORMERS = 'kong.config.use_default_response_transformers';

    const RESPONSE_CLASS = 'kong.config.response.class';

    const FORM_NORMALIZER = 'kong.config.form_normalizer';
}

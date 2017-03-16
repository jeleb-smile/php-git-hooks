<?php

namespace PhpGitHooks\Command;

final class GoodJobLogo
{
    /**
     * @param string $message
     *
     * @return string
     */
    public static function paint($message)
    {
        return sprintf("<fg=yellow;options=bold;>
                 @@@@@@@@@@@@@@@
     @@@@      @@@@@@@@@@@@@@@@@@@
    @    @   @@@@@@@@@@@@@@@@@@@@@@@
    @    @  @@@@@@@@   @@@   @@@@@@@@@
    @   @  @@@@@@@@@   @@@   @@@@@@@@@@
    @  @   @@@@@@@@@@@@@@@@@@@@@@@@@@@@@
   @@@@@@@@@ @@@@@@@@@@@@@@@@@@@@@@@@@@@@
  @         @ @@  @@@@@@@@@@@@@  @@@@@@@@
 @@         @ @@@  @@@@@@@@@@@  @@@@@@@@@
@@   @@@@@@@@ @@@@  @@@@@@@@@  @@@@@@@@@@
@            @ @@@@           @@@@@@@@@@
@@           @ @@@@@@@@@@@@@@@@@@@@@@@@
 @   @@@@@@@@@ @@@@@@@@@@@@@@@@@@@@@@@
 @@         @ @@@@@@@@@@@@@@@@@@@@@@
  @@@@@@@@@@   @@@@@@@@@@@@@@@@@@@
                 @@@@@@@@@@@@@@@
        </fg=yellow;options=bold;>\n
        <fg=white;bg=yellow;options=bold;>       %s       </fg=white;bg=yellow;options=bold;>\n
        <fg=white;bg=blue;options=bold;>Warning: Add all modified files to git before launch again this command!!</fg=white;bg=blue;options=bold;>\n", $message);
    }
}

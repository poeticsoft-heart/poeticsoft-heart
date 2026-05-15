
# Custom Poeticsoft Heart Configuration
export LS_OPTIONS='--color=auto'
eval "$(dircolors)"
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'

# Aliases to avoid mistakes
alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'

PS1="\[\e[1;36m\]deby en \w> \[\e[0m\]"
alias gemini='node ~/gemini-node/gemini.mjs'
export TERM=xterm-256color
export COLORTERM=truecolor
cd /home/heart
export COMPOSER_ALLOW_SUPERUSER=1
